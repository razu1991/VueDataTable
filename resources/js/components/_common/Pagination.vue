<template>
    <div>
        <div
            class="d-flex justify-content-between align-items-center flex-wrap tbl-footer border-top pt-2"
            v-if="meta.total"
        >
            <div>
                <div class="dataTables_info pl-1">
                    Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }}
                    entries total
                </div>
            </div>

            <div v-if="meta.total && meta.last_page > 1">
                <div class="dataTables_paginate paging_full_numbers pull-right">
                    <ul class="pagination pt-1 mb-2" v-if="meta.last_page < 5">
                        <li class="paginate_button page-item">
                            <a href="#" class="page-link" @click.prevent="broadcast(1)">
                                First
                            </a>
                        </li>
                        <li
                            v-for="(page, key) in meta.last_page"
                            :key="key"
                            class="paginate_button page-item"
                            :class="{ active: meta.current_page == page }"
                        >
                            <a href="#" class="page-link" @click.prevent="broadcast(page)">{{
                                page
                                }}</a>
                        </li>
                        <li class="paginate_button page-item">
                            <a
                                href="#"
                                class="page-link"
                                @click.prevent="broadcast(meta.last_page)"
                            >
                                Last
                            </a>
                        </li>
                    </ul>

                    <ul class="pagination pt-1 mb-2" v-else>
                        <li
                            class="paginate_button page-item first"
                            :class="{ disabled: meta.current_page == 1 }"
                        >
                            <a href="#" class="page-link" @click.prevent="broadcast(1)">
                                <i class="fa fa-angle-double-left"></i>
                            </a>
                        </li>

                        <li
                            class="paginate_button page-item previous"
                            :class="{ disabled: meta.current_page == 1 }"
                        >
                            <a
                                href="#"
                                class="page-link"
                                @click.prevent="broadcast(meta.current_page - 1)"
                            >
                                <i class="fa fa-angle-left"></i>
                            </a>
                        </li>

                        <li
                            v-for="(page, key) in _.range(min_page, max_page)"
                            :key="key"
                            class="paginate_button page-item"
                            :class="{ active: meta.current_page == page }"
                        >
                            <a href="#" class="page-link" @click.prevent="broadcast(page)">{{
                                page
                                }}</a>
                        </li>

                        <li
                            class="paginate_button page-item next"
                            :class="{
              disabled: meta.current_page == meta.last_page
            }"
                        >
                            <a
                                href="#"
                                class="page-link"
                                @click.prevent="broadcast(meta.current_page + 1)"
                            >
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>

                        <li
                            class="paginate_button page-item last"
                            :class="{
              disabled: meta.current_page == meta.last_page
            }"
                        >
                            <a
                                href="#"
                                class="page-link"
                                @click.prevent="broadcast(meta.last_page)"
                            >
                                <i class="fa fa-angle-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    props: {
        meta: Object
    },

    data() {
        return {
            page: 0
        };
    },

    computed: {
        min_page() {
            let min_page = this.meta.current_page - 2;
            if (min_page <= 0) return 1;
            else if (min_page + 5 >= this.meta.last_page)
                return this.meta.last_page - 4;
            else return this.meta.current_page - 2;
        },
        max_page() {
            let max_page = this.min_page + 5;
            if (max_page > this.meta.last_page) return this.meta.last_page + 1;
            else return max_page;
        },
        gotoPage() {
            const gotoData = [];
            for (let i = 5; i <= this.meta.last_page; i += 5) {
                gotoData.push(i);
            }
            return gotoData;
        }
    },

    methods: {
        broadcast(page) {
            this.$emit("changed", page);
        }
    }
};
</script>
