<?php namespace ohmy\Auth2\Flow\ThreeLegged;

/*
 * Copyright (c) 2014, Yahoo! Inc. All rights reserved.
 * Copyrights licensed under the New BSD License.
 * See the accompanying LICENSE file for terms.
 */

use ohmy\Auth\Promise,
    ohmy\Components\Http;

class Refresh extends Promise {

    public $client;

    public function __construct($callback, Http $client=null) {
        parent::__construct($callback);
        $this->client = $client;
    }

    public function access($url, Array $options=array()) {
        $self = $this;
        $access = new Access(function($resolve, $reject) use($self, $url, $options) {
            $self->client->POST($url, array(
                'grant_type'    => 'refresh_token',
                'refresh_token' => $self->value['refresh_token'],
                'client_id'     => $self->value['client_id'],
                'client_secret' => $self->value['client_secret']
            ))
            ->then(function($response) use($resolve) {
                $resolve($response);
            });

        }, $this->client);

        return $access;
    }
}
