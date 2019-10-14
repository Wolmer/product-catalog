<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h3>Product Manager</h3>
                                    </div>
                                    <div class="col-sm-4">
                                        <button class="btn btn-success float-right" v-on:click="createProduct()">Create New</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="text" v-model="table.search" placeholder="Search name"/>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped border">
                            <thead>
                                <tr>
                                    <th scope="col" v-on:click="sort('id')">#</th>
                                    <th scope="col" v-on:click="sort('name')">Name</th>
                                    <th scope="col" v-on:click="sort('price')">Price</th>
                                    <th scope="col" v-on:click="sort('category.name')">Category</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(product, index) in filteredList" :key="product.id">
                                    <td>{{ product.id }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.price | currencydecimal }}</td>
                                    <td>{{ product.category.name }}</td>
                                    <td>
                                        <button class="btn btn-success" v-on:click="showProduct(product)">View</button>
                                        <button class="btn btn-primary" v-on:click="editProduct(product)">Edit</button>
                                        <button class="btn btn-danger" v-on:click="deleteProduct(index)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <span class="float-left text-center w-100 p-3">
                            <button class="btn btn-primary float-left"
                                    v-on:click="prevPage"
                                    :disabled="table.currentPage == 1"
                            >Previous</button>

                            Page {{ table.currentPage }} of {{ table.totalPages }}

                            <button class="btn btn-primary float-right"
                                    v-on:click="nextPage"
                                    :disabled="table.currentPage >= table.totalPages"
                            >Next</button>
                        </span>
                    </div>
                </div>

                <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productModalLabel">
                                    {{ modal.title }}
                                    <span v-if="modal.product.id > 0">(ID: {{ modal.product.id }})</span>
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="inputName">Name</label>
                                    <input type="text" class="form-control" id="inputName"
                                            placeholder="Enter name"
                                            v-model="modal.product.name"
                                            :disabled="modal.edit == false"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="inputDescription">Description</label>
                                    <textarea type="text" class="form-control" id="inputDescription"
                                            placeholder="Enter description"
                                            v-model="modal.product.description"
                                            :disabled="modal.edit == false"
                                    ></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="inputCategory">Category</label>
                                    <select class="form-control" id="inputCategory"
                                            v-model="modal.product.category.id"
                                            :disabled="modal.edit == false"
                                    >
                                        <option v-bind:value="0" :selected="true">Select category</option>
                                        <option v-for="(option) in categories" :key="option.id" v-bind:value="option.id" >
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="inputPrice">Price</label>
                                    <input type="number" class="form-control" id="inputPrice"
                                            placeholder="Enter price"
                                            v-model="modal.product.price"
                                            :disabled="modal.edit == false"
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="inputImage">Image</label>
                                    <input type="file" class="form-control" id="inputImage" placeholder="Select image">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <span v-if="modal.edit">Cancel</span>
                                    <span v-else>Close</span>
                                </button>
                                <button type="button" class="btn btn-primary" v-on:click="saveProduct()" v-if="modal.edit">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                categories: [],
                products: [],
                table: {
                    search: '',
                    pageSize: 10,
                    totalPages: 1,
                    currentPage: 1,
                    currentSort: 'name',
                    currentSortDir: 'asc'
                },
                modal: {
                    edit: false,
                    title: 'Create Product',
                    product: {
                        id: 0,
                        name: '',
                        description: '',
                        price: 0,
                        image: '',
                        category: {
                            id: 0,
                            name: '',
                        }
                    },
                    category: 0,
                    action: '/api/products',
                }
            }
        },
        filters: {
            currencydecimal (value) {
                return "$ " + Number(value).toFixed(2)
            }
        },
        computed: {
            filteredList() {
                let filteredProducts = this.products
                    .filter(products => {
                        return products.name.toLowerCase().includes(
                            this.table.search.toLowerCase()
                        )
                    })

                    .sort((a, b) => {
                        let modifier = 1;
                        if(this.table.currentSortDir === 'desc') modifier = -1;
                        if(a[this.table.currentSort] < b[this.table.currentSort]) return -1 * modifier;
                        if(a[this.table.currentSort] > b[this.table.currentSort]) return 1 * modifier;
                        return 0;
                    });

                this.table.totalPages = Math.ceil(filteredProducts.length / this.table.pageSize);

                let paginatedProducts  = filteredProducts
                    .filter((row, index) => {
                        let start = (this.table.currentPage - 1) * this.table.pageSize;
                        let end   = this.table.currentPage * this.table.pageSize;

                        if(this.table.currentPage > this.table.totalPages) this.table.currentPage = 1;
                        if(index >= start && index < end) return true;
                    });

                return paginatedProducts;
            }
        },
        methods: {
            nextPage() {
                if(this.table.currentPage < this.table.totalPages) this.table.currentPage++;
            },

            prevPage() {
                if(this.table.currentPage > 1) this.table.currentPage--;
            },

            sort(column) {
                console.log('filtering sort by ' + column);
                if(column === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
                }
                this.currentSort = column;
            },

            showProduct(item) {
                this.modal.edit    = false;
                this.modal.title   = 'View Product';
                this.modal.product = item;
                this.selectCategory(item.category.id);
                this.openModal();
            },

            createProduct() {
                this.modal.edit    = true;
                this.modal.title   = 'New Product';
                this.modal.action  = '/api/products/create';
                this.modal.product = {
                    id: null,
                    name: null,
                    description: null,
                    price: null,
                    image: null,
                    category: {
                        id: 0,
                        name: null,
                    }
                };
                this.openModal();
            },

            editProduct(item) {
                this.modal.edit    = true;
                this.modal.title   = 'Edit Product';
                this.modal.action  = '/api/products/update/' + item.id;
                this.modal.product = item;
                this.openModal();
            },

            deleteProduct(key) {
                var uri = '/api/products/delete/' + this.products[key].id;

                axios.delete(uri).then(response => {
                    this.products.splice(key, 1);
                });
            },

            saveProduct() {
                this.closeModal();
                axios
                    .post(this.modal.action, this.modal.product)
                    .then((response) => {
                        this.getProducts();
                    })
                    .catch(error => console.log(error));
            },

            selectCategory(id) {
                this.modal.category = id;
            },

            getProducts() {
                axios
                    .get('/api/products')
                    .then(response => {
                        this.products = response.data.data;
                    })
                    .catch(error => console.log(error));
            },

            getCategories() {
                axios
                    .get('/api/categories')
                    .then(response => (this.categories = response.data.data))
                    .catch(error => console.log(error));
            },

            openModal() {
                $('#productModal').modal('show');
            },

            closeModal() {
                $('#productModal').modal('hide');
            }
        },
        mounted() {
            this.getProducts();
            this.getCategories();
        }
    }
</script>
