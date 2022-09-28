@extends('layouts.app')

@push('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Form Produk</div>

                    <div class="card-body">
                        @livewire('produk.create', ['form_title' => 'Tambah Produk'])
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Produk</div>

                    <div class="card-body">
                        @livewire('produk.index', ['name' => 'list produk'])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

{{-- @push('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tarif Produk</div>

                    <div class="card-body">
                        @livewire('tarif', ['name' => 'list tarif'])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush --}}
