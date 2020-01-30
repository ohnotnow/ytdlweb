<div wire:poll.5s>
    <ul>
        @foreach ($files as $file)
            <li class="mb-4">
                @if (!$file->is_complete)
                    <span>{{ $file->url }}</span>
                    <span class="inline-block border border-blue-500 rounded px-2 bg-blue-500 text-white">{{ $file->percent }}%</span>
                    <span class="inline-block border border-blue-500 rounded px-2 bg-blue-500 text-white">{{ $file->eta }}</span>
                    <span class="inline-block border border-blue-500 rounded px-2 bg-blue-500 text-white">{{ $file->speed }}</span>
                @else
                    <span>{{  $file->title }}</span>
                    <span class="inline-block border border-green-500 rounded px-2 bg-green-500 text-white">
                        Complete
                    </span>
                @endif
                @if ($file->is_gubbed)
                    <span class="inline-block border border-red-500 rounded px-2 bg-red-500 text-white">
                        {{ $file->error_message }}
                    </span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
