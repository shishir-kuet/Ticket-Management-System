@extends('layouts.app')

@section('title', 'Contact Sales - Resolve AI')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-center mb-8">Contact Our Sales Team</h1>
        
        <div class="text-center mb-8">
            <p class="text-gray-600 mb-4">
                Interested in our Enterprise plan? Let's discuss how we can help your organization.
            </p>
        </div>

        <form action="#" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="company" id="company" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label for="team_size" class="block text-sm font-medium text-gray-700">Team Size</label>
                <select name="team_size" id="team_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select team size</option>
                    <option value="10-50">10-50 employees</option>
                    <option value="51-200">51-200 employees</option>
                    <option value="201-500">201-500 employees</option>
                    <option value="501+">501+ employees</option>
                </select>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="message" id="message" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
            </div>

            <div class="flex items-center">
                <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Contact Sales
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Prefer to email? Contact us directly at</p>
            <a href="mailto:enterprise@resolveai.com" class="text-blue-600 hover:text-blue-500">enterprise@resolveai.com</a>
        </div>
    </div>
</div>
@endsection