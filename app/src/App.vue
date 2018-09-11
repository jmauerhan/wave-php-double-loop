<template>
    <div id="app">
        <v-container grid-list-xl text-xs-center>
            <v-layout row wrap>
                <v-flex xs12 md6>
                    <h4 class="display-2">Chirper</h4>
                    <v-textarea label="What's going on?" v-model="chirp.attributes.text"></v-textarea>
                    <v-text-field label="Username" v-model="chirp.attributes.author"></v-text-field>
                    <v-btn dark color="green" @click="addItem">Chirp!</v-btn>
                </v-flex>
                <v-flex xs12 md6>
                    <v-list three-line>
                        <template v-for="(item, index) in items">
                            <v-list-tile
                                    :key="item.title"
                                    avatar
                                    @click>
                                <v-list-tile-content>
                                    <v-list-tile-sub-title class="text--primary"
                                                           v-html="item.attributes.text"></v-list-tile-sub-title>
                                    <v-list-tile-sub-title class="caption"
                                                           v-html="item.attributes.author"></v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-list-tile-action-text>{{ item.attributes.created_at }}</v-list-tile-action-text>
                                    <v-btn flat icon color="yellow darken-2">
                                        <v-icon>star_border</v-icon>
                                    </v-btn>
                                </v-list-tile-action>
                            </v-list-tile>
                            <v-divider :key="index"></v-divider>
                        </template>
                    </v-list>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import axios from "axios";
    import moment from "moment"
    import uuidv4 from "uuid/v4"

    export default {
        name: 'app',
        data() {
            return {
                chirp: {
                    id: "",
                    type: "chirp",
                    attributes: {
                        text: "",
                        author: "",
                        created_at: "",
                    }
                },
                items: []
            }
        },
        methods: {
            addItem: function () {
                let chirp = Object.assign({},this.chirp);
                chirp.attributes.created_at = moment().format("YYYY-MM-D hh:mm:ss")
                chirp.id = uuidv4()
                axios.post("http://localhost:3001/chirp", {data: chirp})
                    .then(result => {
                            this.items.unshift(chirp);
                            this.chirp.attributes = {
                                text: "",
                                author: ""
                            }
                        }
                    )
            }
        },
        mounted() {
            axios.get("http://localhost:3001/").then(result => {
                this.items = result.data.data;
            });
        },
    }
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>
