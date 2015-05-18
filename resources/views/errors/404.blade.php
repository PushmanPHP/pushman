<?php $className = 'nav-home'; ?>
@extends('app')

@section('container')

    <div class="cover">
        <div class="container container-cover">
            @include('vendor.flash.message', ['cover' => true])

            <div class="row bottom40">
                <div class="col-lg-12">
                    <img src="/img/logo.png" alt="Pushman Logo">
                    <h1>404 Not Found</h1>
                    <p>This page cannot be found sorry :/</p>
                </div>
            </div>
        </div>
    </div>

@endsection