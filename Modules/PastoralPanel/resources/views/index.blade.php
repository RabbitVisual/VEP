{{-- Legacy index: redirect is handled by PastoralPanelController::index --}}
<x-pastoralpanel::layouts.master title="Área Pastoral">
    <p class="text-slate-400">Redirecionando ao dashboard…</p>
    <script>window.location.replace("{{ route('pastoral.dashboard') }}");</script>
</x-pastoralpanel::layouts.master>
