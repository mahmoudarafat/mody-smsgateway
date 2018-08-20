<?php

namespace mody\smsgateway\traits;

use mody\smsgateway\Models\Provider;
use mody\smsgateway\Models\ProviderParameter;

trait ProviderConfig
{

    protected function storeProvider($request)
    {
        $provider = new Provider();
        $provider->company_name = $request->api_company;
        $provider->api_url = $request->api_url;
//        $provider->username = $request->api_username;
//        $provider->password = $request->api_password;
        $provider->destination_attr = $request->api_destination;
        $provider->message_attr = $request->api_message;
        $provider->success_code = $request->api_success_code;
        $provider->unicode = $request->api_unicode ? 1 : 0;
        $provider->http_method = $request->api_method;
        $provider->default = 0;
        $provider->group_id = session('group_id');
        $provider->user_id = auth()->user() ? auth()->user()->id : null;
        $provider->save();
        return $provider;
    }

    protected function storeAdditionalParams($provider_id, $names, $values)
    {

        foreach($names as $key => $name) {
            $params = new ProviderParameter();
            $params->parameter = $name;
            $params->value = $values[$key];
            $params->group_id = session('group_id');
            $params->user_id = auth()->user() ? auth()->user()->id : null;
            $params->sms_provider_id = $provider_id;
            $params->save();
        }
    }

    public function validateRequest($request)
    {
        $rules = [
            'api_username' => 'string',
            'api_password' => 'string',
            'api_company' => 'required',
            'api_url' => 'required|unique:sms_providers,api_url',
            'api_method' => 'required',
            'api_destination' => 'required',
            'api_message' => 'required',
            'api_success_code' => 'required',

        ];

        $messages = [
            'api_username' => '',
            'api_password' => '',
            'api_company.required' => trans('smsgateway::smsgateway.attributes.company_name'),
            'api_url.required' => trans('smsgateway::smsgateway.attributes.url_required'),
            'api_url.unique' => trans('smsgateway::smsgateway.attributes.url_unique'),
            'api_method.required' => trans('smsgateway::smsgateway.attributes.http_method'),
            'api_destination.required' => trans('smsgateway::smsgateway.attributes.destination_attr'),
            'api_message.required' => trans('smsgateway::smsgateway.attributes.message_attr'),
            'api_success_code.required' => trans('smsgateway::smsgateway.attributes.success_code'),
        ];
        $this->validate($request, $rules, $messages);
    }

    private function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }



}