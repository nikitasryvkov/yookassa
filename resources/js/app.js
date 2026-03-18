import './bootstrap';

import { createApp } from 'vue';
import Header from './components/Header.vue';
import UserPaymentTypes from './components/UserPaymentTypes.vue';
import SbpSearch from './components/SbpSearch.vue';
import BotPaymentsSearch from './components/BotPaymentsSearch.vue';
import CommissionsSearch from "./components/CommissionsSearch.vue";
import AccountBalance from "./components/AccountBalance.vue";

import WelcomePage from './pages/WelcomePage.vue';
import SbpPayments from './pages/SbpPayments.vue';
import InternetPayments from './pages/InternetPayments.vue';
import Tabs from './components/Tabs.vue';
import SetTelegramWebhookButton from './components/SetTelegramWebhookButton.vue';
import PaymentUrlForm from './components/PaymentUrlForm.vue';
import DeleteUserBtn from './components/DeleteUserBtn.vue';
import DeletePointBtn from './components/DeletePointBtn.vue';
import AgentCommissionPaymentRequestBtn from "./components/AgentCommissionPaymentRequestBtn.vue";
import ToggleBlock from "./components/ToggleBlock.vue";

createApp({})
    .component('HeaderVue', Header)
    .component('WelcomePage', WelcomePage)
    .component('SbpPayments', SbpPayments)
    .component('BotPaymentsSearch', BotPaymentsSearch)
    .component('CommissionsSearch', CommissionsSearch)
    .component('SbpSearch', SbpSearch)
    .component('InternetPayments', InternetPayments)
    .component('UserPaymentTypes', UserPaymentTypes)
    .component('Tabs', Tabs)
    .component('SetTelegramWebhookButton', SetTelegramWebhookButton)
    .component('PaymentUrlForm', PaymentUrlForm)
    .component('DeleteUserBtn', DeleteUserBtn)
    .component('DeletePointBtn', DeletePointBtn)
    .component('AgentCommissionPaymentRequestBtn', AgentCommissionPaymentRequestBtn)
    .component('AccountBalance', AccountBalance)
    .component('ToggleBlock', ToggleBlock)
    .mount('#app')
