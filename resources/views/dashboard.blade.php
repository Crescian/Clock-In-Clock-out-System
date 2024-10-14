
@push('styles')
<style>
.loader {
    width: 48px;
    height: 48px;
    border: 5px solid;
    border-color: #FF3D00 transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
  }
  
  @keyframes rotation {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  } 
</style>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div x-data="dashboard('admin')">
        <div class="py-12"> <!-- Moved x-data here -->
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-between gap-4">
                            <div>
                                <button x-on:click.prevent="$dispatch('open-modal', 'confirm-user-clock-in')" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Clock In
                                </button>
                            </div>
                            <div>
                                <button x-on:click.prevent="$dispatch('open-modal', 'confirm-user-clock-out')" type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    Clock Out
                                </button>
                            </div>
                        </div>
                        
                        <x-modal name="confirm-user-clock-in" focusable>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Clock In') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Are you sure you want to Clock In?') }}
                                </p>
                                <div class="flex justify-end mt-6">
                                    <button x-on:click="$dispatch('close')" type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        Cancel
                                    </button>
                                    <button type="button" x-on:click="$dispatch('close')" @click="attendanceRecord({{ auth()->user()->id }}, 'clock-in')" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        Yes
                                    </button>
                                </div>
                            </div>
                        </x-modal>
                        
                        <x-modal name="confirm-user-clock-out" focusable>
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Clock Out') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Are you sure you want to Clock Out?') }}
                                </p>
                                <div class="flex justify-end mt-6">
                                    <button x-on:click="$dispatch('close')" type="button" class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        Cancel
                                    </button>
                                    <button type="button" x-on:click="$dispatch('close')" @click="attendanceRecord({{ auth()->user()->id }}, 'clock-out')" class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                        Yes
                                    </button>
                                </div>
                            </div>
                        </x-modal>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-2">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="relative overflow-x-auto">
                            <table   style="overflow: hidden" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Time
                                        </th>
                                    </tr>
                                </thead>
                                <template x-if="attendanceListLoading">
                                    <tbody>
                                        <tr class="text-center">
                                            <td class="text-center" colspan="5">
                                                <span class="loader"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </template>
                                <template x-if="!attendanceListLoading">
                                    <tbody>
                                        
                                        <template x-if="(fetchAttendance ?? []).length == 0">
                                            <tr class="text-center">
                                                <td class="" colspan="5"><i class="fa fa-info-circle"></i> There is no attendance record for this date.</td>
                                            </tr>
                                        </template>
                                        <template x-if="(fetchAttendance ?? []).length > 0">
                                            <template x-for="(row, data) in fetchAttendance">
                                                {{-- <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"> --}}
                                                    <tr>
                                                        <th x-text="row.name"scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"></th>
                                                        <td x-text="row.clock_status === 'clock-in' ? 'In' : 'Out'" :class="row.clock_status === 'clock-in' ? 'text-blue-500' : 'text-orange-500'" class="px-6 py-4"></td>
                                                        <td x-text="new Date(row.time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })" class="px-6 py-4"></td>
                                                    </tr>
                                                {{-- </tr> --}}

                                            </template>
                                                {{-- <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    Apple MacBook Pro 17"
                                                </th>
                                                <td class="px-6 py-4">
                                                    Silver
                                                </td>
                                                <td class="px-6 py-4">
                                                    $2999
                                                </td> --}}
                                        </template>
                                    </tbody>
                                </template>
                            </table>
                        </div>
                    </div>        
                </div>        
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script src="{{ asset('template/js/dashboard.js') }}"></script>

