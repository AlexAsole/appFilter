<template>
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <input
                type="text"
                name="selected"
                v-model="selected"
                @keyup.enter="filterApp"
            />
            <select
                name="category"
                id="category"
                v-model="category"
                @change="filterCategory"
            >
                <option value="Generale">Generale</option>
                <option
                    v-for="category in allcategory"
                    :key="category"
                    :value="category"
                    >{{ category }}</option
                >
            </select>
        </nav>

        <div class="row justify-content-center">
            <div
                class="col-md-2 py-2"
                v-for="(app, index) in apps"
                :key="index"
            >
                <div class="card h-100">
                    <div class="card-header">{{ app["im:name"].label }}</div>
                    <div class="card-body">
                        <img :src="app['im:image'][2].label" alt="" />
                        <button
                            type="button"
                            class="btn btn-primary aa-margin"
                            data-toggle="modal"
                            :data-target="'#' + 'id' + index"
                        >
                            Mostra altro
                        </button>
                    </div>
                </div>
                <div
                    class="modal fade"
                    :id="'id' + index"
                    tabindex="-1"
                    role="dialog"
                    aria-labelledby="exampleModalCenterTitle"
                    aria-hidden="true"
                >
                    <div
                        class="modal-dialog modal-dialog-centered"
                        role="document"
                    >
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5
                                    class="modal-title"
                                    id="exampleModalLongTitle"
                                >
                                    {{ app["im:name"].label }}
                                </h5>
                                <button
                                    type="button"
                                    class="close"
                                    data-dismiss="modal"
                                    aria-label="Close"
                                >
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {{ app.summary.label }}
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-dismiss="modal"
                                >
                                    Close
                                </button>
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
    data() {
        return {
            selected: "",
            category: "",
            allcategory: [],
            apps: []
        };
    },
    methods: {
        filterApp: function() {
            if (this.selected.length > 0) {
                axios
                    .get(`http://127.0.0.1:8000/api/app?name=` + this.selected)
                    .then(response => {
                        this.apps = response.data;
                    });
            } else {
                axios.get("http://127.0.0.1:8000/api/app").then(response => {
                    this.apps = [];
                    let all = response.data.feed;
                    all.entry.forEach(element => {
                        this.apps.push(element);
                    });
                });
            }
        },
        filterCategory: function() {
            if (this.category !== "Generale") {
                axios
                    .get(
                        "http://127.0.0.1:8000/api/app?category=" +
                            this.category
                    )
                    .then(response => {
                        this.apps = response.data;
                    });
            } else {
                axios.get("http://127.0.0.1:8000/api/app").then(response => {
                    this.apps = [];
                    let all = response.data.feed;
                    all.entry.forEach(element => {
                        this.apps.push(element);
                    });
                });
            }
        }
    },
    mounted() {
        axios.get("http://127.0.0.1:8000/api/app").then(response => {
            let all = response.data.feed;
            all.entry.forEach(element => {
                if (
                    !this.allcategory.includes(
                        element.category.attributes.label
                    )
                ) {
                    this.allcategory.push(element.category.attributes.label);
                }
                this.apps.push(element);
            });
        });
    }
};
</script>
