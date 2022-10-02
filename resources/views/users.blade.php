@extends('layouts.master')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-4 showHideColumn">
                <div class="form-check form-switch form-check-inline">
                    <input type="checkbox" id="id" class="form-check-input" v-model="userIdColumn">
                    <label for="id" class="form-check-label">ID</label>
                </div>
                <div class="form-check form-switch form-check-inline">
                    <input type="checkbox" id="name" class="form-check-input" v-model="userNameColumn">
                    <label for="name" class="form-check-label">Name</label>
                </div>
                <div class="form-check form-switch form-check-inline">
                    <input type="checkbox" id="email" class="form-check-input" v-model="userEmailColumn">
                    <label for="email" class="form-check-label">Email</label>
                </div>
                <div class="form-check form-switch form-check-inline">
                    <input type="checkbox" id="phone" class="form-check-input" v-model="userPhoneColumn">
                    <label for="phone" class="form-check-label">Phone</label>
                </div>
            </div>
            <div class="col-3 offset-2 searchData">
                <input type="text" class="form-control" placeholder="Name | Email | Phone" v-model="search">
            </div>
            <div class="col-3 printData">
                <a href="{{url('print/csv')}}">
                    <button type="button" class="btn btn-secondary">CSV</button>
                </a>
                <a href="{{url('print/pdf')}}">
                    <button type="button" class="btn btn-secondary">PDF</button>
                </a>
                <a href="{{url('print/excel')}}">
                    <button type="button" class="btn btn-secondary">Excel</button>
                </a>
            </div>
            <table id="userListing" class="table table-bordered table-hover" v-if="!loading">
                <thead>
                <tr>
                    <th v-if="userIdColumn">
                        ID
                        <span @click="sortId(1)">&uarr;</span>
                        <span @click="sortId(2)">&darr;</span>
                    </th>
                    <th v-if="userNameColumn">
                        Name
                        <span @click="sortName(1)">&uarr;</span>
                        <span @click="sortName(2)">&darr;</span>
                    </th>
                    <th v-if="userEmailColumn">
                        Email
                        <span @click="sortEmail(1)">&uarr;</span>
                        <span @click="sortEmail(2)">&darr;</span>
                    </th>
                    <th v-if="userPhoneColumn">
                        Phone
                        <span @click="sortPhone(1)">&uarr;</span>
                        <span @click="sortPhone(2)">&darr;</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(user,index) in filteredItems">
                    <td v-if="userIdColumn">@{{index+1}}</td>
                    <td v-if="userNameColumn">@{{user.name}}</td>
                    <td v-if="userEmailColumn">@{{user.email}}</td>
                    <td v-if="userPhoneColumn">@{{user.phone}}</td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
@endsection
@push('script')
    <script>
        const {createApp} = Vue

        createApp({
            mounted() {
                this.getAllUsers();
            },
            data() {
                return {
                    loading: false,
                    all_users: [],
                    search: '',
                    userIdColumn: true,
                    userNameColumn: true,
                    userEmailColumn: true,
                    userPhoneColumn: true
                }
            },
            methods: {
                getAllUsers() {
                    this.loading = true;
                    axios.get('/users').then(({data}) => {
                        this.loading = false;
                        this.all_users = data.data;
                    }).catch();
                },
                sortId(type) {
                    this.all_users.sort(type == 1 ? (a, b) => a.id > b.id ? 1 : -1 : (a, b) => a.id < b.id ? 1 : -1)
                },
                sortName(type) {
                    this.all_users.sort(type == 1 ? (a, b) => a.name > b.name ? 1 : -1 : (a, b) => a.name < b.name ? 1 : -1)
                },
                sortEmail(type) {
                    this.all_users.sort(type == 1 ? (a, b) => a.email > b.email ? 1 : -1 : (a, b) => a.email < b.email ? 1 : -1)
                },
                sortPhone(type) {
                    this.all_users.sort(type == 1 ? (a, b) => a.phone > b.phone ? 1 : -1 : (a, b) => a.phone < b.phone ? 1 : -1)
                },
            },
            computed: {
                filteredItems() {
                    if (!this.search) return this.all_users
                    return this.all_users.filter(user => {
                        return (user.name.toLowerCase().includes(this.search.toLowerCase()) || user.email.toLowerCase().includes(this.search.toLowerCase()) || user.phone.toLowerCase().includes(this.search.toLowerCase()));
                    })
                }
            }
        }).mount('#app')
    </script>
@endpush
