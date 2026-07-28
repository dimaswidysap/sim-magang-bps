@extends('layouts.app')

@section('content')

    <main class=" relative w-full flex bg-background">
        {{-- container-sidebar-admin --}}

        @include('components.asn.asn-sidebar')

        {{-- container-content --}}
        <section class="flex flex-col flex-1 pl-60">

            {{-- header --}}
            @include('components.admin.header-admin')

            <section class="w-full p-2">
                <section class="container-dalam">
                    @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('asn-create-task') }}">
                        @csrf

                        <label>Judul Tugas</label><br>
                        <input type="text" name="judul" value="{{ old('judul') }}"><br><br>

                        <label>Deskripsi</label><br>
                        <textarea name="deskripsi" rows="5" cols="50">{{ old('deskripsi') }}</textarea><br><br>

                        <label>Deadline</label><br>
                        <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"><br><br>

                        <button type="submit">Simpan Tugas</button>
                    </form>
                </section>
            </section>
        </section>
    </main>

@endsection
