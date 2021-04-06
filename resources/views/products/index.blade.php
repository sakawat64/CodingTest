@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{route('search')}}" method="post" class="card-header">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        @foreach($variants as $variant)
                        <optgroup label="{{$variant->title}}">
                            @php
                                $product_variants = DB::table('product_variants')
                                                       ->select('variant')
                                                       ->distinct()
                                                       ->where('variant_id',$variant->id)
                                                       ->get();
                            @endphp
                            @foreach($product_variants as $product_variant)
                            <option value="{{$product_variant->variant}}">{{$product_variant->variant}}</option>
                            @endforeach
                        </optgroup>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @php $serial = 1; @endphp
{{--                    {{dd($data['products'])}}--}}
                    @foreach($data['products'] as $key => $product)
                    <tr>
                        <td>{{$serial++}}</td>
                        <td>{{$product->title}} <br> Created at: {{date('d-M-Y',strtotime($product->created_at))}}</td>
                        <td>{{$product->description}}</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                @foreach($data['products_variant_prices'][$key] as $key2=> $products_variant_price)
                                    @foreach($data['product_variant'][$key][$key2] as $product_variant)
                                <dt class="col-sm-3 pb-0">
                                    {{$product_variant->variant}}/
                                </dt>
                                    @endforeach
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format($products_variant_price->price) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format($products_variant_price->stock) }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $data['products']->firstItem() }} to {{ $data['products']->lastItem() }} out of {{$data['products']->total()}}</p>
                </div>
                <div class="col-md-2">
                    {{$data['products']->links()}}
                </div>
            </div>
        </div>
    </div>

@endsection
