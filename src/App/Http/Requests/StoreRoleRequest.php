<?php

namespace jeremykenedy\LaravelRoles\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (config('roles.rolesGuiCreateNewRolesMiddlewareType') == 'role') {
            return $this->user()->hasRole(config('roles.rolesGuiCreateNewRolesMiddleware'));
        }
        if (config('roles.rolesGuiCreateNewRolesMiddlewareType') == 'permissions') {
            return $this->user()->hasPermission(config('roles.rolesGuiCreateNewRolesMiddleware'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|unique:'.config('roles.rolesTable').',name,'.$this->id.',id',
            'slug'          => 'required|unique:'.config('roles.rolesTable').',slug,'.$this->id.',id',
            'description'   => 'nullable|string|max:255',
            'level'         => 'required|integer',
            'service_provider_id'=> 'nullable|exist:service_providers,id',
        ];
    }

    /**
     * Return the fields and values to create a new role.
     *
     * @return array
     */
    public function roleFillData()
    {
        return [
            'name'          => $this->name,
            'slug'          => $this->slug,
            'description'   => $this->description,
            'level'         => $this->level,
            'service_provider_id' => Auth::user()->service_provider_id,
        ];
    }
}
