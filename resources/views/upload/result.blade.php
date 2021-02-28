<x-layout>
    <x-slot name="title">Upload Results</x-slot>

    <div class="ml-12">
        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
            <ul>
                <li>Type: {{ $type }}</li>
                <li>Records Parsed: {{ $parsed }}</li>
                <li>Records Saved: {{ $saved }}</li>
                @if ($type == 'Meter Usage')
                    <li>Discrepancies
                        <ul>
                            <li>On-peak Subtotal: {{ $discrepancies['onPeakSubTotal'] }}</li>
                            <li>Off-peak Subtotal: {{ $discrepancies['offPeakSubTotal'] }}</li>
                            <li>Total: {{ $discrepancies['total'] }}</li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</x-layout>
