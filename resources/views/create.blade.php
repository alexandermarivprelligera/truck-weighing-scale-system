@extends('layout')

@section('title', 'Create Transaction - Truck Weighing Scale System')

@section('content')

    <div class="space-y-4 mb-6">
        <a href="/dashboard" class="bg-gray-500 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>

<h2 class="text-lg font-semibold">Purchase Transaction</h2>

<form method="POST" action="/transactions" class="space-y-4">
    @csrf
<input type="hidden" name="transaction_no" value="{{ $transactionNo }}">
    <!-- Plate / Rep / Driver -->
    <div class="grid grid-cols-3 gap-4">
        <!--div class="mb-4"-->
        <!--label-- class="font-semibold">Transaction No.</!--label-->
        

        <input type="text" value="{{ $transactionNo }}" readonly class="border rounded p-2 bg-gray-100"> <!--/div-->
        
        <input type="text" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="plate_number" placeholder="Plate Number">
        <!--input type="text" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="representative_name" placeholder="Representative Name"-->
        <input type="text" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="driver_name" placeholder="Driver Name">
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
                    data-address="{{ $client->address }}">
                    {{ $client->company }}
                </option>
            @endforeach
        </select>

        <!--input type="text" id="company" name="company" class="w-full p-2 border rounded" placeholder="New Client"-->

        <input type="text" id="address" class="p-2 border rounded w-full focus:ring-2 focus:ring-blue-500" name="address" placeholder="Address">
    </div>

    <!-- Materials / Product / Moisture -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block mb-2">Transaction Type</label>

            <select name="transaction_type" id="transaction_type"
                class="w-full p-2 border rounded">
                <option value="incoming">Incoming</option>
                <option value="outgoing">Outgoing</option>
            </select>
        </div>

        <div id="materials_section">
            <label class="block mb-2">Materials</label>

            <div class="grid grid-cols-4 gap-2">
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Solid Waste"> Solid Waste
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Minerals"> Minerals
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Bottles"> Bottles
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="material" value="Tin Can"> Tin Can
                </label>
            </div>
        </div>

        <div id="product_section">
            <label class="block mb-2">Product</label>
            <input type="text" name="product" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
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
            <input type="number" id="gross_weight" name="gross_weight" class="p-2 border rounded w-full bg-white-100" placeholder="Gross Weight">

            <button type="button" onclick="captureWeight('gross')" class="mt-2 bg-orange-600 text-white px-3 py-2 rounded 
                hover:bg-orange-900 transition">
                Capture Gross
            </button>
        </div>

        <div>
            <input type="number" id="tare_weight" name="tare_weight" class="p-2 border rounded w-full bg-white-100 focus:ring-2 focus:ring-blue-500" placeholder="Tare Weight">

            <button type="button" onclick="captureWeight('tare')"class="mt-2 bg-purple-600 text-white px-3 py-2 rounded 
                hover:bg-purple-900 transition">
                Capture Tare
            </button>
        </div>

        <div>
            <input type="number" id="net_weight" name="net_weight" readonly class="p-2 border rounded w-full bg-white-100" placeholder="Net Weight">
        </div>

    </div>

    <div class="grid grid-cols-3 gap-4">
        <input type="hidden" name="gross_time" id="gross_time">
        <!--input type="hidden" name="tare_time" id="tare_time">
        <input type="hidden" name="net_time" id="net_time"-->
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
            Save Transaction
        </button>

        <!--a href="/print" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-900 transition">
            Print Receipt
        </!--a-->

        <button type="submit" formaction="/print-preview" formtarget="_blank" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-900">
            Print Receipt
        </button>
</form>



<script>
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
    document.getElementById('gross_weight').addEventListener('change', function () {
        if(this.value !== ''){
            setTime('gross_time');
            calculateNetWeight();
            
            if(document.getElementById('tare_weight').value){
                setTime('net_time');
            }
        }
    });


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