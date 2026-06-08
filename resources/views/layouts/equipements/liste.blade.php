@extends('layouts.app')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">
				
				
			</h3>
			<nav aria-label="breadcrumb">
				<ul class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page">
						<span></span>
						Liste des équipements
						<i class="mdi mdi-desktop-tower-monitor icon-sm text-primary align-middle"></i>
					</li>
				</ul>
			</nav>
			<div class="d-flex justify-content-end mt-3">
				<a href="{{ route('equipements.create') }}" class="btn btn-gradient-primary btn-icon-text">
					<i class="mdi mdi-plus btn-icon-prepend"></i>
					Ajouter un équipement
				</a>
			</div>
		</div>

		<div class="row">
			<div class="col-12 grid-margin">
				<div class="card">
					<div class="card-body">

						@if (session('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
						@endif

						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>No</th>
										<th>Nom</th>
										<th>Type</th>
										<th>État</th>
										<th>Station</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									@forelse($equipements as $equipement)
									<tr>
										<td>{{ $loop->iteration }}</td>
										<td>{{ $equipement->nom }}</td>
										<td>{{ $equipement->type }}</td>
										<td>
											@if ($equipement->etat === 'fonctionnel')
											<span class="badge badge-gradient-success">Fonctionnel</span>
											@elseif ($equipement->etat === 'en_panne')
											<span class="badge badge-gradient-warning">En panne</span>
											@else
											<span class="badge badge-gradient-danger">Critique</span>
											@endif
										</td>
										<td>{{ optional($equipement->station)->nom ?? 'N/A' }}</td>
										<td>
											<a href="{{ route('equipements.edit', $equipement->id) }}" class="btn btn-sm btn-outline-primary">
												<i class="mdi mdi-pencil"></i> Modifier
											</a>
											<form action="{{ route('equipements.destroy', $equipement->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="btn btn-sm btn-outline-danger">
													<i class="mdi mdi-delete"></i> Supprimer
												</button>
											</form>
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="6" class="text-center">Aucun équipement trouvé.</td>
									</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection