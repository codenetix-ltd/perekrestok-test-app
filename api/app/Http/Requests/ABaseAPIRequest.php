<?php

namespace App\Http\Requests;

use App\QueryParams\EmptyQueryParamsObject;
use App\QueryParams\IQueryParamsObject;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class ABaseAPIRequest extends Request implements ValidatesWhenResolved
{

    /**
     * The container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * @var IQueryParamsObject
     */
    protected $queryParamsObjectInstance;

    /**
     * @var null|Model
     */
    protected $cachedModel = null;

    /**
     * Create the validator factory instance for the request.
     *
     * @return ValidationFactory
     */
    protected function createValidatorFactory(): ValidationFactory
    {
        return $this->container->make(ValidationFactory::class);
    }

    /**
     * @param ValidationFactory $factory
     * @return Validator
     */
    protected function createDefaultValidator(ValidationFactory $factory): Validator
    {
        return $factory->make(
            $this->post(),
            $this->container->call([$this, 'rules']),
            $this->messages(),
            $this->attributes()
        );
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated(): array
    {
        $rules = $this->container->call([$this, 'rules']);

        return $this->only(collect($rules)->keys()->map(function ($rule) {
            return explode('.', $rule)[0];
        })->unique()->toArray());
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * Set the container implementation.
     *
     * @param  \Illuminate\Contracts\Container\Container $container
     * @return $this
     */
    public function setContainer(Container $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Validate the class instance.
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateResolved(): void
    {
        if (!$this->authorize($this->model())) {
            throw new AccessDeniedHttpException();
        }

        $factoryInstance = $this->createValidatorFactory();
        $bodyValidatorInstance = $this->createDefaultValidator($factoryInstance);

        if (! $bodyValidatorInstance->passes()) {
            $this->failedValidation($bodyValidatorInstance);
        }
    }

    /**
     * @param Validator $validator
     * @throws ValidationException
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }

    /**
     * @return IQueryParamsObject
     */
    public function queryParamsObject(): IQueryParamsObject
    {
        return $this->queryParamsObjectInstance ?: $this->createQueryParamsObject();
    }

    /**
     * @return IQueryParamsObject
     */
    protected function createQueryParamsObject(): IQueryParamsObject
    {
        return new EmptyQueryParamsObject([], [], [], []);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * @return mixed|null
     */
    public function model(): ?Model
    {
        if (!$this->cachedModel && method_exists($this, 'getTargetModel')) {
            $this->cachedModel = $this->container->call([$this, 'getTargetModel']);
        }

        return $this->cachedModel;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function filtered(): array
    {
        return $this->only(array_keys($this->rules()));
    }
}
