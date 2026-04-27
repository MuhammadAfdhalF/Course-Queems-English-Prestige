<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    @foreach ($metrics as $metric)
        <x-admin.metric-card
            :title="$metric['title']"
            :value="$metric['value']"
            :description="$metric['description']"
            :accent="$metric['accent'] ?? 'blue'"
            :icon="$metric['icon']" />
    @endforeach
</div>