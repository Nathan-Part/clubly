@extends('pages_site.fond')

@section('titre', 'Administration - Validations')

@section('titre_contenu')
    <h1>Demandes d'inscription en attente</h1>
@endsection

@section('contenu')
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($users->isEmpty())
        <p>Aucune demande d'inscription en attente.</p>
    @else
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nom</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Email</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $user->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $user->email }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <form action="{{ route('admin.valider', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background-color: #4CAF50; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">Valider</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
