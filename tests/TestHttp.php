<?php

namespace Tests;

use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;

trait TestHttp
{
    /**
     * URL that will be used for the request.
     */
    protected private(set) string $url = '';

    /**
     * Set the URL for the request.
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Call a GET request and assert the Inertia response.
     *
     * @param  \Closure(AssertableInertia): (AssertableInertia)  $callback
     */
    public function assertInertiaGet(\Closure $callback): TestResponse
    {
        return $this->get($this->url)->assertOk()->assertInertia($callback);
    }

    /**
     * Call a POST request and assert the Inertia redirect response.
     *
     * @param  array<string,mixed>  $data
     */
    public function assertInertiaPost(array $data, ?string $redirectTo = null): TestResponse
    {
        $response = $this->post($this->url, $data)->assertRedirect($redirectTo);

        $response->assertSessionHasNoErrors();

        return $response;
    }

    /**
     * Call a PUT request and assert the Inertia redirect response.
     *
     * @param  array<string,mixed>  $data
     */
    public function assertInertiaPut(array $data, ?string $redirectTo = null): TestResponse
    {
        $response = $this->put($this->url, $data)->assertRedirect($redirectTo);

        $response->assertSessionHasNoErrors();

        return $response;
    }

    /**
     * Call a DELETE request and assert the Inertia redirect response.
     *
     * @param  array<string,mixed>  $data
     */
    public function assertInertiaDelete(array $data = [], ?string $redirectTo = null): TestResponse
    {
        $response = $this->delete($this->url, $data)->assertRedirect($redirectTo);

        $response->assertSessionHasNoErrors();

        return $response;
    }

    /**
     * Translate validation error messages the same way Laravel does,
     * so expected messages in tests match actual :attribute output.
     *
     * @return array<string,mixed>
     */
    private function prepareInertiaErrors(array $errors): array
    {
        $preparedErrors = [];

        foreach ($errors as $key => $error) {
            $langKey = "validation.attributes.{$key}";
            $attribute = Lang::get($langKey);
            $message = str_replace(
                search: [':attribute', ':Attribute'],
                replace: [$attribute, ucfirst($attribute)],
                subject: $error
            );
            $preparedErrors[$key] = $message;

            $this->assertStringNotContainsString('validation.', $message);
        }

        return $preparedErrors;
    }

    /**
     * Call a POST request and assert it redirects back with validation errors.
     *
     * @param  array<string,mixed>  $data
     * @param  array<string,mixed>  $errors
     */
    public function assertInertiaPostErrors(array $data, array $errors): TestResponse
    {
        return $this->post($this->url, $data)
            ->assertRedirect()
            ->assertSessionHasErrors($this->prepareInertiaErrors($errors));
    }

    /**
     * Call a PUT request and assert it redirects back with validation errors.
     *
     * @param  array<string,mixed>  $data
     * @param  array<string,mixed>  $errors
     */
    public function assertInertiaPutErrors(array $data, array $errors): TestResponse
    {
        return $this->put($this->url, $data)
            ->assertRedirect()
            ->assertSessionHasErrors($this->prepareInertiaErrors($errors));
    }

    /**
     * Call a DELETE request and assert it redirects back with validation errors.
     *
     * @param  array<string,mixed>  $data
     * @param  array<string,mixed>  $errors
     */
    public function assertInertiaDeleteErrors(array $data, array $errors): TestResponse
    {
        return $this->delete($this->url, $data)
            ->assertRedirect()
            ->assertSessionHasErrors($this->prepareInertiaErrors($errors));
    }
}
