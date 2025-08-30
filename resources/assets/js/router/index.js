import Vue from 'vue'
import VueRouter from 'vue-router'
import clients from './clients'
import sellers from './sellers'
import vendors from './vendors'
import products from './products'
import quotations from './quotations'
import advancePayments from './advance_payments'
import SalesOrders from './sales_orders'
import invoices from './invoices'
import clientPayments from './client_payments'
import expenses from './expenses'
import purchaseOrders from './purchase_orders'
import bills from './bills'
import vendorPayments from './vendor_payments'
import users from './users'
import receiveOrders from './receive_orders'

import custom_query from './custom_query'


import customer_returns_report from './customer_returns_report'
import customer_returns from './customer_returns'

import cost_changes_report from './cost_changes_report'


import product_dropdown_1 from './product_dropdown_1'
import product_dropdown_2 from './product_dropdown_2'

import client_dropdown_1 from './client_dropdown_1'
import client_dropdown_2 from './client_dropdown_2'



import payment_options_report from './payment_options_report'

import goodsIssue from './goods_issue'
import stock_movement from './stock_movement'
import notifications from './notifications'
import brands from './brands'
import container_receive_orders from './container_receive_orders'

import subsubcategories from './subsubcategories'

import clients_balance_report from './clients_balance_report'

import import_products from './import_products'

import deliverycondition from './deliverycondition'
import paymentcondition from './paymentcondition'
import payment_options from './payment_options'

import exchangerate from './exchangerate'
import uom from './uom'
import accounts from './accounts'
import employees from './employees'
import employees_report from './employees_report'
import payroll from './payroll'
import deposits from './deposits'
import return_deposits from './return_deposits'

import statement from './statement'
import vendor_statement from './vendor_statement'
import invoices_report from './invoices_report'
import purchase_orders_report from './purchase_orders_report'

import container_orders_report from './container_orders_report'
import products_report from './products_report'
import products_catalogue from './products_catalogue'

import quotations_report from './quotations_report'
import sales_orders_report from './sales_orders_report'
import advance_payments_report from './advance_payments_report'
import credit_notes_report from './credit_notes_report'
import debit_notes_report from './debit_notes_report'
import client_payments_report from './client_payments_report'
import expenses_report from './expenses_report'
import vendor_payments_report from './vendor_payments_report'
import vendor_bills_report from './vendor_bills_report'
import receive_orders_report from './receive_orders_report'

import transfer_accounts from './transfer_accounts'
import counters from './counters'

import creditNotes from './credit_notes'
import debitNotes from './debit_notes'

import warehouses_report_criteria from './warehouses_report_criteria'
import categories from './categories'
import subcategories from './subcategories'
import warehouses from './warehouses'
import currencies from './currencies'
import transfers from './transfers'
import products_division from './products_division'
import products_aggregation from './products_aggregation'

import machines from './machines'
import finished_product_type from './finished_product_type'
import finished_product from './finished_product'
import attributes from './attributes'
import raw_material_type from './raw_material_type'
import job_order from './job_order'
import damaged_deteriorate from './damaged_deteriorate'

import general_settings from './general_settings'
import sidebar_reports from './sidebar_reports'

import products_history from './products_history'

import chart_of_accounts from './chart_of_accounts'
import journal_vouchers from './journal_vouchers'
import journal_vouchers_movement from './journal_vouchers_movement'
import trial_balance_report from './trial_balance_report'
import journal_vouchers_flow from './journal_vouchers_flow'
import third_parties_extras from './third_parties_extras'

import seller_statement from './seller_statement'
import container_orders from './container_orders'
import shippers from './shippers'
import shipperbills from './shipperbills'
import shipper_payments from './shipper_payments'


import vat_accounts from './vat_accounts'
import receipt_vouchers from './receipt_vouchers'
import payment_vouchers from './payment_vouchers'
import stock_count from './stock_count'
import balance_sheet from './balance_sheet'
import general_ledger from './general_ledger'
import profit_loss from './profit_loss'
import product_lots from './product_lots'

import finish_product_image from './finish_product_image'

import seller_payments_docs_report from './seller_payments_docs_report'
import product_image from './product_image'

import seller_payments from './seller_payments'
import seller_payments_docs from './seller_payments_docs'
import shipper_statement from './shipper_statement'
import price_changes_report from './price_changes_report'

import PersonalSettings from '../views/settings/personal.vue'
import EmailDocument from '../views/email/document.vue'
import Settings from '../views/settings/form.vue'
import NotFound from '../views/error/not_found.vue'

import vatrate from './vatrate'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
    routes: [
        {path: '/', component: require('../views/dashboard/index.vue')},
        ...clients,
        ...custom_query,
        ...chart_of_accounts,
        ...journal_vouchers,
        ...journal_vouchers_movement,
        ...journal_vouchers_flow,
        ...trial_balance_report,
        ...third_parties_extras,
        ...vat_accounts,
        ...receipt_vouchers,
        ...payment_vouchers,
        ...stock_count,
        ...balance_sheet,
        ...general_ledger,
        ...profit_loss,
        ...product_lots,
        ...sellers,
        ...seller_payments,
        ...seller_payments_docs,
        ...seller_statement,
        ...deliverycondition,
        ...paymentcondition,
        ...payment_options,
        ...exchangerate,
        ...shipperbills,
        ...shipper_payments,
        ...product_dropdown_1,
        ...product_dropdown_2,
        ...clients_balance_report,
        ...container_orders,
        ...shippers,
        ...uom,
        ...brands,
        ...payment_options_report,
        ...subsubcategories,
        ...products_report,
        ...customer_returns,
        ...customer_returns_report,
        ...cost_changes_report,
        ...product_image,
        ...price_changes_report,
        ...import_products,
        ...products_history,
        ...client_dropdown_1,
        ...client_dropdown_2,
        ...products_catalogue,
        ...seller_payments_docs_report,
        ...shipper_statement,
        ...container_orders_report,
        ...finished_product_type,
        ...finished_product,
        ...job_order,
        ...container_receive_orders,
		...damaged_deteriorate,
        ...attributes,
        ...raw_material_type,
        ...machines,
        ...accounts,
        ...notifications,
        ...general_settings,
        ...sidebar_reports,
        ...vatrate,
        ...stock_movement,
        ...employees,
        ...employees_report,
        ...payroll,
        ...deposits,
        ...return_deposits,
        ...vendors,
        ...products,
        ...quotations,
        ...advancePayments,
        ...SalesOrders,
        ...invoices,
        ...invoices_report,
        ...purchase_orders_report,
        ...quotations_report,
        ...sales_orders_report,
        ...advance_payments_report,
        ...credit_notes_report,
        ...debit_notes_report,
        ...client_payments_report,
        ...expenses_report,
        ...vendor_bills_report,
        ...vendor_payments_report,
        ...receive_orders_report,
        ...counters,
        ...transfer_accounts,
        ...warehouses_report_criteria,
        ...categories,
        ...subcategories,
        ...warehouses,
        ...transfers,
        ...products_division,
        ...products_aggregation,
        ...currencies,
        ...clientPayments,
        ...expenses,
        ...purchaseOrders,
        ...bills,
        ...vendorPayments,
        ...users,
        ...receiveOrders,
        ...goodsIssue,
        ...statement,
        ...vendor_statement,
        ...finish_product_image,
        ...creditNotes,
        ...debitNotes,
        // single views
        {path: '/personal-settings', component: PersonalSettings},
        {path: '/email/:id/:type', component: EmailDocument},
        {path: '/settings', component: Settings},
        {path: '*', component: debitNotes}
    ]
})

export default router
