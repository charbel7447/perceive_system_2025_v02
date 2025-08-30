<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-heading">Apply Product Filter</div>
                    <input type="text" class="form-control" v-model="form.vendor_id">
                    <div class="panel-footer">
                        <div class="btn-group">
                            <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                                Save
                            </button>
                            <button :disabled="isProcessing" @click="closeModal" class="btn">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </transition>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get } from '../../lib/api'
    import { form } from '../../lib/mixins'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    export default {
        components: {ErrorText, Spinner},
        mixins: [form],
        data() {
            return {
                resource: `/products/`,
                store: `/api/productsf`,
                method: 'POST',
                message: 'You have successfully applied payment to invoices!',
                form: {
                    currency: {},
                    client: {},
                    items: []
                }
            }
        },
        methods: {
            closeModal() {
                this.$emit('close')
            },
            fetchData() {
                get(this.url)
                    .then((response) => {
                        if(response.data) {
                            Vue.set(this.$data, 'form', response.data.data)
                            this.show = true
                        }
                    })
                    .catch((error) => {
                        if(error.response.status === 422) {
                            Vue.set(this.$data, 'error', error.response.data)
                        }
                    })
            },
            save() {
                this.submit((data) => {
                    this.success()
                    this.closeModal()
                    this.$router.push(`xxx`)
                })
            }
        }
    }
</script>
