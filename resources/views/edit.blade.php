@extends('layout')

@section('title', 'Create Transaction - Truck Weighing Scale System')

@section('content')

    <div class="space-y-4 mb-6">
        <a href="/transactions" class="bg-gray-500 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>

<h2 class="text-lg font-semibold">Purchase Transaction</h2>

<form method="POST" action="/transactions/{{ $transaction->id }}" class="space-y-4">
    @csrf
    @method('PUT')

<input type="hidden" name="transaction_no" value="{{ $transaction->transaction_no }}">
    <!-- Plate / Rep / Driver -->
    <div class="grid grid-cols-3 gap-4">
        <!--div class="mb-4"-->
        <!--label-- class="font-semibold">Transaction No.</!--label-->
        

        <input type="text" value="{{ $transaction->transaction_no }}" readonly class="border rounded p-2 bg-gray-100"> <!--/div-->
        
        <input type="text" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="plate_number" 
            value="{{ old('plate_number', $transaction->plate_number) }}" placeholder="Plate Number">
        <!--input type="text" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="representative_name" placeholder="Representative Name"-->
        <input type="text" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="driver_name" 
            value="{{ old('driver_name', $transaction->driver_name) }}" placeholder="Driver Name">
    </div>

    <!-- Address / Company -->
    <div class="grid grid-cols-2 gap-4">
        <select id="company_select" name="company" class="w-full p-2 border rounded">
            <option value="">
                Select Client
            </option>
            @foreach($clients as $client)
                <option
                    value="{{ $client->company }}"
                    {{ $transaction->company == $client->company ? 'selected' : '' }}
                    data-address="{{ $client->address }}">
                    {{ $client->company }}
                </option>
            @endforeach
        </select>

        <!--input type="text" id="company" name="company" class="w-full p-2 border rounded" placeholder="New Client"-->

        <input type="text" id="address" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="address" 
            value="{{ old('address',$transaction->address) }}" placeholder="Address">
    </div>

    <!-- Materials / Product / Moisture -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block mb-2">Transaction Type</label>

            <select name="transaction_type" id="transaction_type"
                class="w-full p-2 border rounded">
                <option value="incoming"
                {{ $transaction->transaction_type == 'incoming' ? 'selected' : '' }}>
                Incoming
                </option>

                <option value="outgoing"
                {{ $transaction->transaction_type == 'outgoing' ? 'selected' : '' }}>
                Outgoing
                </option>
            </select>
        </div>

        <div id="materials_section">
            <label class="block mb-2">Materials</label>

            <div class="grid grid-cols-4 gap-2">
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Solid Waste" {{ $transaction->material == 'Solid Waste' ? 'checked' : '' }}> Solid Waste
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Minerals" {{ $transaction->material == 'Minerals' ? 'checked' : '' }}> Minerals
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Bottles" {{ $transaction->material == 'Bottles' ? 'checked' : '' }}> Bottles
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Tin Can" {{ $transaction->material == 'Tin Can' ? 'checked' : '' }}> Tin Can
                </label>
            </div>
        </div>

        <div id="product_section">
            <label class="block mb-2">Product</label>
            <input type="text" name="product" value="{{ old('product',$transaction->product) }}" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
        </div>

        <!--div>
            <label class="block mb-2">Moisture Content</label>
            <input type="text" name="moisture_content" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
        </!--div-->

    </div>

    <!-- Weights -->

    <div class="bg-gray-900 text-green-400 text-3xl p-4 rounded text-center">
        <span id="live_weight">0</span> kg
    </div>


    <div class="grid grid-cols-3 gap-4">

        <div>
            <input type="number" id="gross_weight" name="gross_weight" class="p-2 border rounded w-full bg-white-100" 
            value="{{ old('gross_weight',$transaction->gross_weight) }}" placeholder="Gross Weight">

            <button type="button" onclick="captureWeight('gross')" class="mt-2 bg-orange-600 text-white px-3 py-2 rounded 
                hover:bg-orange-900 transition">
                Capture Gross
            </button>
        </div>

        <div>
            <input type="number" id="tare_weight" name="tare_weight" class="p-2 border rounded w-full bg-white-100 focus:ring-2 focus:ring-blue-500" value="{{ old('tare_weight',$transaction->tare_weight) }}" placeholder="Tare Weight">

            <button type="button" onclick="captureWeight('tare')"class="mt-2 bg-purple-600 text-white px-3 py-2 rounded 
                hover:bg-purple-900 transition">
                Capture Tare
            </button>
        </div>

        <div>
            <input type="number" id="net_weight" name="net_weight" readonly class="p-2 border rounded w-full bg-white-100" 
            value="{{ old('net_weight',$transaction->net_weight) }}"    placeholder="Net Weight">
        </div>

    </div>

    <div class="grid grid-cols-3 gap-4">
        <input type="hidden" name="gross_time" id="gross_time" value="{{ $transaction->gross_time }}">
        <input type="hidden" name="tare_time" id="tare_time">
        <input type="hidden" name="net_time" id="net_time">
    </div>

    <!--div class="grid grid-cols-2 gap-4"-->
        <!-- Clerk -->
        <!--div-->
        <!--label-- class="block mb-2 gap-x-1">Clerk</!--label-->
        <!--input type="text" value="{{ auth()->user()->name }}" readonly class="w-full p-2 border rounded"-->
        <!--/div-->

        <!-- Admin -->
        <!--div>
            <label class="block mb-2 gap-x-1">Approving Admin</label>
            <select name="approved_by" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                <option value="">Select Admin</option>
                <option>Admin 1</option>
                <option>Admin 2</option>
            </select>
        </!--div>
    </-div-->

    <!-- Buttons -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-900 transition">
            Update Transaction
        </button>

        <!--a href="/print" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-900 transition">
            Print Receipt
        </!--a-->
</form>

<form action="/print-preview" method="POST" target="_blank" class="mt-3">
    @csrf

    <input type="hidden" name="transaction_no" id="print_transaction_no" value="{{ $transaction->transaction_no }}">
    <input type="hidden" name="plate_number"  id="print_plate_number" value="{{ $transaction->plate_number }}">
    <input type="hidden" name="driver_name"  id="print_driver_name" value="{{ $transaction->driver_name }}">
    <input type="hidden" name="company"  id="print_company" value="{{ $transaction->company }}">
    <input type="hidden" name="address"  id="print_address" value="{{ $transaction->address }}">
    <input type="hidden" name="transaction_type"  id="print_transaction_type" value="{{ $transaction->transaction_type }}">
    <input type="hidden" name="material"  id="print_material" value="{{ $transaction->material }}">
    <input type="hidden" name="product"  id="print_product" value="{{ $transaction->product }}">
    <input type="hidden" name="gross_weight"  id="print_gross_weight" value="{{ $transaction->gross_weight }}">
    <input type="hidden" name="tare_weight"  id="print_tare_weight" value="{{ $transaction->tare_weight }}">
    <input type="hidden" name="net_weight"  id="print_net_weight" value="{{ $transaction->net_weight }}">
    <input type="hidden" name="gross_time"  id="print_gross_time" value="{{ $transaction->gross_time }}">
    <input type="hidden" name="tare_time"  id="print_tare_time" value="{{ $transaction->tare_time }}">
    <input type="hidden" name="net_time"  id="print_net_time" value="{{ $transaction->net_time }}">
    <input type="hidden" name="clerk" value="{{ auth()->user()->name }}">

        <button type="submit" onClick="preparePrint()" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-900">
            Print Receipt
        </button>
</form>



<script>
//======================
// PRINT EDITED FORM
//======================
    function preparePrint() {
        document.getElementById('print_transaction_no').value = document.querySelector('input[name="transaction_no"]').value;
        document.getElementById('print_plate_number').value = document.querySelector('input[name="plate_number"]').value;
        document.getElementById('print_driver_name').value = document.querySelector('input[name="driver_name"]').value;
        document.getElementById('print_company').value = document.querySelector('select[name="company"]').value;
        document.getElementById('print_address').value = document.querySelector('input[name="address"]').value;
        document.getElementById('print_transaction_type').value = document.querySelector('select[name="transaction_type"]').value;
        let material = document.querySelector('input[name="material"]:checked');
        document.getElementById('print_material').value = material ? material.value : "";
        document.getElementById('print_product').value = document.querySelector('input[name="product"]').value;
        document.getElementById('print_gross_weight').value = document.getElementById('gross_weight').value;
        document.getElementById('print_tare_weight').value = document.getElementById('tare_weight').value;
        document.getElementById('print_net_weight').value = document.getElementById('net_weight').value;
        document.getElementById('print_gross_time').value = document.getElementById('gross_time').value;
        document.getElementById('print_tare_time').value = document.getElementById('tare_time').value;
        document.getElementById('print_net_time').value = document.getElementById('net_time').value;
}

// ===========================
// TIMESTAMP
// ===========================
function setTime(fieldId)
{
    let now = new Date();
    let formatted =
        now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0') + ' ' +
        String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0') + ':' +
        String(now.getSeconds()).padStart(2, '0');
    document.getElementById(fieldId).value = formatted;
}

// ===========================
// NET WEIGHT
// ===========================
function calculateNetWeight()
{
    let gross =
        parseFloat(
            document.getElementById('gross_weight').value
        ) || 0;

    let tare =
        parseFloat(
            document.getElementById('tare_weight').value
        ) || 0;

    document.getElementById('net_weight').value =
        gross - tare;
}

// ===========================
// CAPTURE SCALE WEIGHT
// ===========================
async function captureWeight(type)
{
    try {
        let res = await fetch('/weight');
        let data = await res.json();

        let weight = parseFloat(data.weight) || 0;

        if(type === 'gross')
        {
            document.getElementById('gross_weight').value = weight;
            setTime('gross_time');
        }

        if(type === 'tare')
        {
            document.getElementById('tare_weight').value = weight;
            setTime('tare_time');
        }

        calculateNetWeight();

        if(
            document.getElementById('gross_weight').value &&
            document.getElementById('tare_weight').value
        ){
            setTime('net_time');
        }

    } catch(error){
        console.error(error);
    }
}

// ===========================
// PAGE LOAD
// ===========================
document.addEventListener('DOMContentLoaded', function ()
{
    // =======================
    // TRANSACTION NUMBER
    // =======================
    const transactionNo =
    document.getElementById('transaction_no');

    if (transactionNo) {
        console.log(transactionNo.value);
    }

    // =======================
    // TRANSACTION TYPE TOGGLE
    // =======================
    const typeSelect =
        document.getElementById('transaction_type');
    const materials =
        document.getElementById('materials_section');
    const product =
        document.getElementById('product_section');

    function toggleFields()
    {
        if(typeSelect.value === 'incoming')
        {
            materials.style.display = 'block';
            product.style.display = 'none';
        }
        else
        {
            materials.style.display = 'none';
            product.style.display = 'block';
        }
    }
    toggleFields();
    typeSelect.addEventListener(
        'change',
        toggleFields
    );

    // =======================
    // CLIENT DROPDOWN
    // =======================
    const companySelect =
        document.getElementById('company_select');
    if(companySelect)
    {
        companySelect.addEventListener(
            'change',
            function ()
            {
                let selected =
                    this.options[this.selectedIndex];
                document.getElementById('address').value =
                    selected.dataset.address || '';
            }
        );
    }
    // =======================
    // MANUAL COMPANY INPUT
    // =======================
    const companyInput =
        document.getElementById('company');

    if(companyInput)
    {
        companyInput.addEventListener(
            'input',
            function ()
            {
                fetch(
                    `/company-address?company=${this.value}`
                )
                .then(res => res.json())
                .then(data =>
                {
                    document.getElementById('address').value =
                        data.address || '';
                });
            }
        );
    }

    // =======================
    // NET WEIGHT EVENTS
    // =======================
    document.getElementById('gross_weight')
        .addEventListener(
            'input',
            calculateNetWeight
        );
    document.getElementById('tare_weight')
        .addEventListener(
            'input',
            calculateNetWeight
        );

    // =======================
    // LIVE SCALE DISPLAY
    // =======================
    setInterval(() =>
    {
        fetch('/weight')
        .then(res => res.json())
        .then(data =>
        {
            const liveWeight =
                document.getElementById('live_weight');
            if(liveWeight)
            {
                liveWeight.innerText =
                    data.weight || '0';
            }
        })
        .catch(error =>
        {
            console.log(
                'Live Weight Error:',
                error
            );
        });
    }, 1000);

    // =======================
    // WEIGHT CAPTURE TIMES
    // =======================
    document.getElementById('tare_weight').addEventListener('change', function () {
    if(this.value !== ''){
        setTime('tare_time');
        calculateNetWeight();

            if(document.getElementById('gross_weight').value){
                setTime('net_time');
            }
        }
    });
    
});
</script>

@endsection