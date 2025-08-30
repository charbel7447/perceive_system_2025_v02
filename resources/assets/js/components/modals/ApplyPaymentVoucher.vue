<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container2">
                    <div class="modal-heading">Apply Receipt Voucher to Bills</div>
                    <div class="modal-body" v-if="show">
                        <div class="row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>Vendor</label>
                                    <span class="form-control">{{ form.vendor.text }}</span>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Currency</label>
                                    <span class="form-control">{{ form.currency.text }}</span>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>RV Number</label>
                                    <span class="form-control">{{ form.number }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>RV Balance Amount</label>
                                    <span class="form-control">{{ form.balance_amount | formatMoney(form.currency) }}</span>
                                    <error-text :error="error.balance_amount"></error-text>
                                    <input style="display:none;" type="text" v-model="form.balance_amount" />
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Exchange Rate</label>
                                    <input type="text" class="form-control" v-model.number="form.exchange_rate" />
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Vat %</label>
                                    <input type="text" class="form-control" v-model="form.global_vat_percentage" />
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-danger" v-if="!form.items.length">
                            <span>There are no sent and partially paid Bills for this vendor.</span>
                        </div>

                        <div v-else>
                            <hr>
                            <table class="item-table">
                                <thead>
                                    <tr>
                                        <th>Bill Number</th>
                                        <th>Bill Date</th>
                                        <th>Bill Total</th>
                                        <th>Balance Due</th>
                                        <th>Currency</th>
                                        <th>Amount Applied</th>
                                        <th>Amount Applied (USD)</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in form.items" :key="index">
                                        <td v-if="toNumber(item.bill_id) > 0">
                                            <a :href="'/bills/' + item.bill_id">
                                                <span style="color: red !important;" class="form-control">{{ item.number }}</span></a>
                                            <input style="display:none;" type="text" class="form-control" v-model="item.number" />
                                            <input style="display:none;" type="text" v-model="item.bill_id" />
                                            
                                        </td>
                                        <td v-else>
                                            <typeahead :initial="form.bills"
                                                       :url="billsURL"
                                                       @input="onBillUpdate(item, index, $event)">
                                            </typeahead>
                                        </td>

                                        <td>
                                            <input type="date" class="form-control" v-model="item.date" />
                                            <input style="display: none;" type="text" class="form-control" v-model="item.status_id" />
                                        </td>

                                        <td>
                                            <input type="number" step="0.001" class="form-control"
                                                   v-model.number="item.total"
                                                   @input="recomputeAllRunningBalances()" />
                                        </td>

                                        <td>
                                            <input type="number" class="form-control"
                                                   v-model.number="item.runningBalance"
                                                   readonly />
                                        </td>

                                        <td>
                                            <typeahead :initial="item.currency"
                                                       :url="currencyURL"
                                                       @input="onCurrencyUpdate(item, index, $event)" />
                                            <error-text :error="error.currency_id"></error-text>
                                            <input style="display:none;" type="text" v-model="item.currency_code" />
                                        </td>

                                        <td>
                                            <input type="number" step="0.001" class="form-control"
                                                   v-model.number="item.amount_applied"
                                                   @input="
                                                       computeAppliedUSD(item);
                                                       recomputeAllRunningBalances();
                                                   " />
                                            <error-text :error="error[`items.${index}.amount_applied`]"></error-text>
                                        </td>

                                        <td>
                                            <!-- Keep USD derived and readonly to avoid desync -->
                                            <input type="number" step="0.001" class="form-control"
                                                   v-model.number="item.amount_applied_usd"
                                                   readonly />
                                        </td>

                                        <td>
                                            <button class="item-remove" @click="removeItem(index)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align:right;padding:0 5px !important;">
                                            <button class="btn btn-primary" @click="addNewLine">
                                                Add new line / إضافة سطر جديد
                                            </button>
                                        </th>
                                        <td colspan="2" class="item-footer">Balance Amount</td>
                                        <td class="item-footer">
                                            <strong class="item-dark form-control">
                                                {{ form.balance_amount | formatMoney(form.currency) }}
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="item-footer" style="text-align:right;">Total Amount Applied (USD)</td>
                                        <td class="item-footer">
                                            <strong class="item-dark form-control">
                                                {{ totalAppliedUSD }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <spinner v-if="isProcessing"></spinner>
                        <div class="btn-group" v-else>
                            <button :disabled="isProcessing" @click="save" class="btn btn-primary" v-if="form.items.length">
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

<script>
import Vue from 'vue'
import { get } from '../../lib/api'
import { form } from '../../lib/mixins'
import ErrorText from '../../components/form/ErrorText.vue'
import Spinner from '../../components/loading/Spinner.vue'
import Typeahead from '../../components/form/Typeahead.vue'

export default {
    components: { ErrorText, Spinner, Typeahead },
    mixins: [form],
    data() {
        return {
            show: false,
            resource: `/payment_vouchers/${this.$route.params.id}`,
            url: `/api/search/payment_vouchers_bills/${this.$route.params.id}`,
            store: `/api/payment_vouchers_bills/${this.$route.params.id}/apply`,
            method: 'POST',
            message: 'You have successfully applied payment to payment_vouchers!',
            currencyURL: '/api/search/currencies',
            billsURL: '/api/search/vendor_bills_confirmed',
            form: {
                currency: {},
                vendor: {},
                exchange_rate: 1,
                items: []
            },
            error: {}
        }
    },
    created() {
        this.fetchData()
    },
    watch: {
        // If exchange rate changes, recompute USD amounts and balances
        'form.exchange_rate'(val) {
            this.form.items.forEach((it) => this.computeAppliedUSD(it))
            this.recomputeAllRunningBalances()
        }
    },
    computed: {
        totalAppliedUSD() {
            return this.form.items.reduce((sum, item) => {
                return sum + this.toNumber(item.amount_applied_usd)
            }, 0)
        }
    },
    methods: {
        /* ---------- Utils ---------- */
        toNumber(v) {
            const n = Number(v)
            return Number.isFinite(n) ? n : 0
        },
        round2(n) {
            return Math.round(this.toNumber(n) * 1000) / 1000
        },

        /* ---------- Core Conversions ---------- */
        computeAppliedUSD(item) {
            // currency_id == 1 => treat as USD
            const inUsd = this.toNumber(item.currency_id) !== 1
                ? this.toNumber(item.amount_applied) / (this.toNumber(this.form.exchange_rate) || 1)
                : this.toNumber(item.amount_applied)
            item.amount_applied_usd = this.round2(inUsd)
        },

        recomputeAllRunningBalances() {
            // cumulative applied per invoice (top → bottom)
            const appliedByInvoice = Object.create(null)

            this.form.items.forEach((row) => {
                const invoiceId = row.bill_id || `__no_invoice_${Math.random()}`
                const total = this.toNumber(row.total)
                const paid = this.toNumber(row.amount_paid)
                const prevApplied = this.toNumber(appliedByInvoice[invoiceId] || 0)

                // running balance formula
                row.runningBalance = this.round2(total - paid - prevApplied)

                // then include this row's applied USD for subsequent rows
                appliedByInvoice[invoiceId] = this.round2(prevApplied + this.toNumber(row.amount_applied_usd))
            })
        },

        /* ---------- CRUD / Events ---------- */
        addNewLine() {
            this.form.items.push({
                bill_id: null,
                number: '',
                date: new Date().toISOString().slice(0, 10),
                currency_id: 1,          // default USD
                currency_code: 'USD',
                currency: { id: 1, code: 'USD', text: 'USD' },
                total: 0,
                amount_paid: 0,
                amount_applied: 0,
                amount_applied_usd: 0,
                runningBalance: 0
            })
            this.recomputeAllRunningBalances()
        },

        removeItem(index) {
            this.form.items.splice(index, 1)
            this.recomputeAllRunningBalances()
        },

        closeModal() {
            this.$emit('close')
        },

        fetchData() {
            get(this.url)
                .then((response) => {
                    if (response.data) {
                        Vue.set(this.$data, 'form', response.data.data)

                        // Ensure numeric types and compute USD for all rows
                        this.form.items.forEach((item) => {
                            item.total = this.toNumber(item.total)
                            item.amount_paid = this.toNumber(item.amount_paid)
                            item.amount_applied = this.toNumber(item.amount_applied)
                            // derive USD each time (keeps consistent after reload)
                            this.computeAppliedUSD(item)
                        })

                        // compute chain balances once after load
                        this.recomputeAllRunningBalances()
                        this.show = true
                    }
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        Vue.set(this.$data, 'error', error.response.data)
                    }
                })
        },

        onCurrencyUpdate(item, index, e) {
            const currency = e.target.value
            Vue.set(this.form.items[index], 'currency_id', currency.id)
            Vue.set(this.form.items[index], 'currency_code', currency.code)
            Vue.set(this.form.items[index], 'currency', currency)

            // reset amounts when currency changes
            Vue.set(this.form.items[index], 'amount_applied', 0)
            this.computeAppliedUSD(this.form.items[index])
            this.recomputeAllRunningBalances()
        },

        onBillUpdate(item, index, e) {
            const bills = e.target.value

            Vue.set(this.form.items[index], 'bill_id', bills.id)
            Vue.set(this.form.items[index], 'number', bills.number)
            Vue.set(this.form.items[index], 'date', bills.date)
            Vue.set(this.form.items[index], 'total', this.toNumber(bills.total))
            Vue.set(this.form.items[index], 'amount_paid', this.toNumber(bills.amount_paid))
            Vue.set(this.form.items[index], 'status_id', bills.status_id)
            Vue.set(this.form.items[index], 'bills', bills)

            // When linking an bill, reset applied amounts for the row
            Vue.set(this.form.items[index], 'amount_applied', 0)
            this.computeAppliedUSD(this.form.items[index])
            this.recomputeAllRunningBalances()
        },

        save() {
            // make sure balances are up to date before submit
            this.form.items.forEach((it) => this.computeAppliedUSD(it))
            this.recomputeAllRunningBalances()

            this.submit((data) => {
                const id = Math.random().toString(36).substring(7)
                this.success()
                this.closeModal()
                this.$router.push(`${this.resource}?success=${id}`)
            })
        },

        setData(res) {
            Vue.set(this.$data, 'form', res.data.form)
            this.$title.set(`Receipt Voucher ${this.title}`)
            this.$bar.finish()
            this.show = true
        }
    }
}
</script>
