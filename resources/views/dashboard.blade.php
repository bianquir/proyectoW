@extends('layouts.landing')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-3 py-2 bg-secondary d-flex flex-column imagendefondo">
        
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                        <div class="row p-1 border-dark">
                            <div class="col-12 input-group p-1">
                                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control bg-transparent" placeholder="Buscar" aria-label="Buscar">
                            </div>
                        </div>
                        <div class="row p-1 d-flex">
                            <div class="col-12">
                                <button class="btn btn-outline-secondary mt-1">Leidos</button>
            
                                <button class="btn btn-outline-secondary mt-1">No Leidos</button>
            
                                <button class="btn btn-outline-secondary mt-1">Grupos</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body overflow-scroll contacto-chats"> 
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="row">
                                    <figure class="col-2 d-flex align-items-center justify-content-center">
                                        <img src="{{asset('img/criticaldevs.png')}}" alt="Perfil">
                                    </figure>
                                    <div class="col-10 d-flex flex-column">
                                        <div class="m-1 d-flex justify-content-between align-items-cneter message-header">
                                            <h4>Jorge el cliente</h4>
                                            <p>11:54</p>
                                        </div>
                                        <textarea class="mensaje-vista-previa" name="" id="" cols="30" rows="1">Mensaje del cliente</textarea>
                                        <div class="d-flex">
                                            <button class="btn btn-secondary mx-1 mt-1">Tag 1</button>
                                            <button class="btn btn-secondary mx-1 mt-1">Tag 2</button>
                                            <button class="btn btn-secondary mx-1 mt-1">Tag 3</button>
                                        </div>
                                    </div>
                                    
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-5 d-flex flex-column">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row p-1 border-dark">
                            <div class="col-12 input-group p-1">
                                <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control bg-transparent" placeholder="Buscar" aria-label="Buscar">
                            </div>
                        </div>
                        <div class="row p-1 d-flex">
                            <div class="col-12">
                                <button class="btn btn-outline-secondary mt-1">Leidos</button>
            
                                <button class="btn btn-outline-secondary mt-1">No Leidos</button>
            
                                <button class="btn btn-outline-secondary mt-1">Grupos</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body messages">
                        Error nulla optio quas provident blanditiis consequuntur maxime magni, et dolorum rerum sunt corporis temporibus ea quaerat suscipit dignissimos sed. Excepturi necessitatibus rem qui. Quas et vitae ducimus architecto eum!Lorem Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus doloribus tempore deleniti quibusdam nobis saepe magni, nihil ducimus quos, molestias est enim alias perspiciatis voluptas aliquid autem earum amet voluptatibus!
                    </div>
                    <div class="card-footer">
                        <form id="chat-form">
                            <div class="mb-3">
                                <label for="message-input" class="form-label">Mensaje:</label>
                                <textarea class="form-control" id="message-input" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 h-100 bg-success d-flex justify-content-center align-items-center">
            <div>
                <a href="">Enviar Mensaje</a>
            </div>
        </div>
    </div>
    </div>
@endsection
