<div class="mb-4">
    <form class="w-full" wire:submit.prevent="queueDownload">
        <div class="flex items-center border-b border-b-2 border-teal-500 py-2">
            <input
              class="appearance-none bg-transparent border-none w-full text-gray-200 mr-3 py-1 px-2 leading-tight focus:outline-none"
              type="text"
              placeholder="Enter a URL..."
              aria-label="URL to download"
              wire:model="url"
            >
            <label class="flex-shrink-0 border-transparent border-4 text-teal-500 hover:text-teal-200 text-sm py-1 px-2 rounded">
                <input class="" type="checkbox" wire:model="extractAudio">
                Extract Audio?
            </label>
            <button
              class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded @if (!$url) cursor-not-allowed @endif"
              type="submit"
              @if (!$url) disabled @endif
            >
                Download
            </button>
        </div>
        @error('url') <span class="error">{{ $message }}</span> @enderror
    </form>
</div>