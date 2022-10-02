<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-4 showHideColumn mb-3">
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
            <div class="col-3 printData">
                <a :href="`/print/csv?name=`+this.searchParam.name+'&email='+this.searchParam.email+'&phone='+this.searchParam.phone">
                    <button type="button" class="btn btn-secondary">CSV</button>
                </a>
                <a :href="`/print/pdf?name=`+this.searchParam.name+'&email='+this.searchParam.email+'&phone='+this.searchParam.phone">
                    <button type="button" class="btn btn-secondary">PDF</button>
                </a>
                <a :href="`/print/excel?name=`+this.searchParam.name+'&email='+this.searchParam.email+'&phone='+this.searchParam.phone">
                    <button type="button" class="btn btn-secondary">Excel</button>
                </a>
            </div>

            <table id="userListing" class="table table-bordered table-hover">
                <thead>
                <tr class="sortColumn">
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
                <tr>
                    <td v-if="userIdColumn">#</td>
                    <td v-if="userNameColumn"><input type="search" class="form-control" placeholder="Filter Name.." @keyup="columnSearch"
                               v-model="searchParam.name"></td>
                    <td v-if="userEmailColumn"><input type="search" class="form-control" placeholder="Filter Email.." @keyup="columnSearch"
                               v-model="searchParam.email"></td>
                    <td v-if="userPhoneColumn"><input type="search" class="form-control" placeholder="Filter Phone.." @keyup="columnSearch"
                               v-model="searchParam.phone"></td>
                </tr>
                <tr v-for="(user,index) in all_users.data" v-if="!loading">
                    <td v-if="userIdColumn">{{ index + 1 }}</td>
                    <td v-if="userNameColumn">{{ user.name }}</td>
                    <td v-if="userEmailColumn">{{ user.email }}</td>
                    <td v-if="userPhoneColumn">{{ user.phone }}</td>
                </tr>
                <tr v-if="loading">
                    <td colspan="4" class="text-center">
                        <span class="m-5">
                            <i class="fas fa-spinner fa-pulse fa-3x"></i>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
            <pagination :meta="all_users" @changed="pagination"></pagination>
        </div>
    </div>
</template>
<script>
import pagination from './_common/Pagination'

export default {
    components: {
        pagination
    },
    mounted() {
        this.changeLimit();
    },
    data() {
        return {
            loading: false,
            all_users: [],
            queryStr: '?',
            limit: 10,
            paginationPage: '',
            userIdColumn: true,
            userNameColumn: true,
            userEmailColumn: true,
            userPhoneColumn: true,
            searchParam: {
                name: "",
                email: "",
                phone: ""
            }
        }
    },
    methods: {

        getAllUsers() {
            this.loading = true;
            axios.get('/users' + this.queryStr).then(({data}) => {
                this.loading = false;
                this.all_users = data;
            }).catch();
        },

        columnSearch() {

            this.queryStr = "?";

            if (this.searchParam.name != "") {
                this.queryStr += "name=" + this.searchParam.name + "&";
            }
            if (this.searchParam.email != "") {
                this.queryStr += "email=" + this.searchParam.email + "&";
            }
            if (this.searchParam.phone != "") {
                this.queryStr += "phone=" + this.searchParam.phone + "&";
            }

            this.changeLimit();
        },

        pagination(data) {
            let page = this.queryStr.split('&')
            if (this.queryStr.includes('page=')) {
                let pageNo = page[0].split('=')[1]
                this.queryStr = this.queryStr.replace('page=' + pageNo, 'page=' + data)
            } else {
                let pages = this.queryStr.split('?')
                pages[0] = '?page=' + data + '&'
                this.queryStr = pages.join('')
            }
            this.changeLimit()
        },

        changeLimit() {
            if (this.queryStr.includes('limit=')) {
                let query = this.queryStr.split('&')
                for (let index = 0; index < query.length; index++) {
                    if (query[index].includes('limit=')) {
                        if (query[index].includes('?')) {
                            query[index] = '?limit=' + this.limit
                        } else {
                            query[index] = 'limit=' + this.limit
                        }
                    }
                }
                this.queryStr = query.join('&')
            } else {
                this.queryStr += 'limit=' + this.limit + '&'
            }
            this.getAllUsers()
        },

        sortId(type) {
            this.all_users.data.sort(type == 1 ? (a, b) => a.id > b.id ? 1 : -1 : (a, b) => a.id < b.id ? 1 : -1)
        },
        sortName(type) {
            this.all_users.data.sort(type == 1 ? (a, b) => a.name > b.name ? 1 : -1 : (a, b) => a.name < b.name ? 1 : -1)
        },
        sortEmail(type) {
            this.all_users.data.sort(type == 1 ? (a, b) => a.email > b.email ? 1 : -1 : (a, b) => a.email < b.email ? 1 : -1)
        },
        sortPhone(type) {
            this.all_users.data.sort(type == 1 ? (a, b) => a.phone > b.phone ? 1 : -1 : (a, b) => a.phone < b.phone ? 1 : -1)
        }

    }
}
</script>
