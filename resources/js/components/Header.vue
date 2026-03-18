<template>
    <header class="bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="/" class="-m-1.5 p-1.5">
                    <span class="sr-only">Home</span>
                    <svg class="w-6 h-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 9.75L12 3l9 6.75V20.25A1.5 1.5 0 0119.5 21h-3a1.5 1.5 0 01-1.5-1.5v-4.5h-6v4.5A1.5 1.5 0 017.5 21h-3A1.5 1.5 0 013 20.25V9.75z"/>
                    </svg>
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = true">
                    <span class="sr-only">Open main menu</span>
                    <Bars3Icon class="size-6" aria-hidden="true" />
                </button>
            </div>
            <PopoverGroup class="hidden lg:flex lg:gap-x-12">
                <Popover class="relative">
                    <PopoverButton class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
                        Мой профиль
                        <ChevronDownIcon class="size-5 flex-none text-gray-400" aria-hidden="true" />
                    </PopoverButton>

                    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <PopoverPanel class="absolute -left-8 top-full z-10 mt-3 w-44 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                            <div v-for="item in myProfile" :key="item.name" class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-gray-50">
                                <div class="flex-auto p-2">
                                    <a :href="item.href" class="block font-semibold text-gray-900">
                                        {{ item.name }}
                                        <span class="absolute inset-0" />
                                    </a>
                                </div>
                            </div>
                        </PopoverPanel>
                    </transition>
                </Popover>

                <Popover class="relative">
                    <PopoverButton class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
                        Платежи
                        <ChevronDownIcon class="size-5 flex-none text-gray-400" aria-hidden="true" />
                    </PopoverButton>

                    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <PopoverPanel class="absolute -left-8 top-full z-10 mt-3 w-44 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                            <div v-for="item in products" :key="item.name" class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-gray-50">
                                <div v-if="isAdmin || item.name === 'Телеграм бот' || item.name === 'Ссылки'" class="flex-auto p-2">
                                    <a :href="item.href" class="block font-semibold text-gray-900">
                                        {{ item.name }}
                                        <span class="absolute inset-0" />
                                    </a>
                                </div>
                            </div>
                        </PopoverPanel>
                    </transition>
                </Popover>

                <Popover v-if="isAdmin" class="relative">
                    <PopoverButton class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
                        Телеграм
                        <ChevronDownIcon class="size-5 flex-none text-gray-400" aria-hidden="true" />
                    </PopoverButton>

                    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <PopoverPanel class="absolute -left-8 top-full z-10 mt-3 w-44 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                            <div class="p-2">
                                <div v-for="item in telegram" :key="item.name" class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-gray-50">
                                    <div class="flex-auto">
                                        <a :href="item.href" class="block font-semibold text-gray-900">
                                            {{ item.name }}
                                            <span class="absolute inset-0" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </PopoverPanel>
                    </transition>
                </Popover>

                <Popover v-if="isAdmin" class="relative">
                    <PopoverButton class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
                        Пользователи
                        <ChevronDownIcon class="size-5 flex-none text-gray-400" aria-hidden="true" />
                    </PopoverButton>

                    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <PopoverPanel class="absolute -left-8 top-full z-10 mt-3 w-44 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                            <div class="p-2">
                                <div v-for="item in users" :key="item.name" class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-gray-50">
                                    <div class="flex-auto">
                                        <a :href="item.href" class="block font-semibold text-gray-900">
                                            {{ item.name }}
                                            <span class="absolute inset-0" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </PopoverPanel>
                    </transition>
                </Popover>

                <Popover v-if="isAdmin" class="relative">
                    <PopoverButton class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
                        Точки продаж
                        <ChevronDownIcon class="size-5 flex-none text-gray-400" aria-hidden="true" />
                    </PopoverButton>

                    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <PopoverPanel class="absolute -left-8 top-full z-10 mt-3 w-44 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                            <div class="p-2">
                                <div v-for="item in paymentPoints" :key="item.name" class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-gray-50">
                                    <div class="flex-auto">
                                        <a :href="item.href" class="block font-semibold text-gray-900">
                                            {{ item.name }}
                                            <span class="absolute inset-0" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </PopoverPanel>
                    </transition>
                </Popover>

                <Popover class="relative">
                    <PopoverButton class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
                        Отчёты
                        <ChevronDownIcon class="size-5 flex-none text-gray-400" aria-hidden="true" />
                    </PopoverButton>

                    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                        <PopoverPanel class="absolute -left-8 top-full z-10 mt-3 w-44 overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                            <div v-for="item in reports" :key="item.name" class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-gray-50">
                                <div class="flex-auto p-2">
                                    <a :href="item.href" class="block font-semibold text-gray-900">
                                        {{ item.name }}
                                        <span class="absolute inset-0" />
                                    </a>
                                </div>
                            </div>
                        </PopoverPanel>
                    </transition>
                </Popover>

            </PopoverGroup>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <slot>

                </slot>
            </div>
        </nav>
        <Dialog class="lg:hidden" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
            <div class="fixed inset-0 z-10" />
            <DialogPanel class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                    <a href="/" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <img class="h-8 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="" />
                    </a>
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
                        <span class="sr-only">Close menu</span>
                        <XMarkIcon class="size-6" aria-hidden="true" />
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            <Disclosure as="div" class="-mx-3" v-slot="{ open }">
                                <DisclosureButton class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">
                                    Мой профиль
                                    <ChevronDownIcon :class="[open ? 'rotate-180' : '', 'size-5 flex-none']" aria-hidden="true" />
                                </DisclosureButton>
                                <DisclosurePanel class="mt-2 space-y-2">
                                    <div v-for="item in myProfile" :key="item.name">
                                        <a
                                           :href="item.href"
                                           class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">
                                            {{ item.name }}
                                        </a>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                            <Disclosure as="div" class="-mx-3" v-slot="{ open }">
                                <DisclosureButton class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">
                                    Платежи
                                    <ChevronDownIcon :class="[open ? 'rotate-180' : '', 'size-5 flex-none']" aria-hidden="true" />
                                </DisclosureButton>
                                <DisclosurePanel class="mt-2 space-y-2">
                                    <div v-for="item in products" :key="item.name">
                                        <a v-if="isAdmin || item.name === 'Телеграм бот' || item.name === 'Ссылки'"
                                           :href="item.href"
                                           class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">
                                            {{ item.name }}
                                        </a>
                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                            <Disclosure v-if="isAdmin" as="div" class="-mx-3" v-slot="{ open }">
                                <DisclosureButton class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">
                                    Телеграм
                                    <ChevronDownIcon :class="[open ? 'rotate-180' : '', 'size-5 flex-none']" aria-hidden="true" />
                                </DisclosureButton>
                                <DisclosurePanel class="mt-2 space-y-2">
                                    <DisclosureButton v-for="item in telegram" :key="item.name" as="a" :href="item.href" class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ item.name }}</DisclosureButton>
                                </DisclosurePanel>
                            </Disclosure>
                            <Disclosure v-if="isAdmin" as="div" class="-mx-3" v-slot="{ open }">
                                <DisclosureButton class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">
                                    Пользователи
                                    <ChevronDownIcon :class="[open ? 'rotate-180' : '', 'size-5 flex-none']" aria-hidden="true" />
                                </DisclosureButton>
                                <DisclosurePanel class="mt-2 space-y-2">
                                    <DisclosureButton v-for="item in users" :key="item.name" as="a" :href="item.href" class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ item.name }}</DisclosureButton>
                                </DisclosurePanel>
                            </Disclosure>

                            <Disclosure v-if="isAdmin" as="div" class="-mx-3" v-slot="{ open }">
                                <DisclosureButton class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">
                                    Точки продаж
                                    <ChevronDownIcon :class="[open ? 'rotate-180' : '', 'size-5 flex-none']" aria-hidden="true" />
                                </DisclosureButton>
                                <DisclosurePanel class="mt-2 space-y-2">
                                    <DisclosureButton v-for="item in paymentPoints" :key="item.name" as="a" :href="item.href" class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ item.name }}</DisclosureButton>
                                </DisclosurePanel>
                            </Disclosure>

                            <Disclosure as="div" class="-mx-3" v-slot="{ open }">
                                <DisclosureButton class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">
                                    Отчёты
                                    <ChevronDownIcon :class="[open ? 'rotate-180' : '', 'size-5 flex-none']" aria-hidden="true" />
                                </DisclosureButton>
                                <DisclosurePanel class="mt-2 space-y-2">
                                    <DisclosureButton v-for="item in reports" :key="item.name" as="a" :href="item.href" class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ item.name }}</DisclosureButton>
                                </DisclosurePanel>
                            </Disclosure>

                        </div>
                        <div class="py-6">
                            <slot>

                            </slot>
                        </div>
                    </div>
                </div>
            </DialogPanel>
        </Dialog>
    </header>
</template>

<script setup>
import { ref } from 'vue'
import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Popover,
    PopoverButton,
    PopoverGroup,
    PopoverPanel,
} from '@headlessui/vue'
import {
    ArrowPathIcon,
    Bars3Icon,
    ChartPieIcon,
    CursorArrowRaysIcon,
    FingerPrintIcon,
    SquaresPlusIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline'
import { ChevronDownIcon, PhoneIcon, PlayCircleIcon } from '@heroicons/vue/20/solid'

const products = [
    { name: 'СБП', href: '/tb/incoming-sbp-payments'},
    { name: 'Телеграм бот', href: '/telegram/payments'},
    { name: 'Ссылки', href: '/links'},
    { name: 'Интернет эквайеринг', href: '/tb/acquiring-internet-payments'},
]

const telegram = [
    { name: 'Создать бота', href: '/bots/create'},
    { name: 'Боты', href: '/bots'},
]

const users = [
    { name: 'Создать', href: '/users/create'},
    { name: 'Список', href: '/users'},
]

const paymentPoints = [
    { name: 'Создать', href: '/payment-points/create'},
    { name: 'Список', href: '/payment-points'},
]

const reports = [
    { name: 'Комиссия', href: '/reports/commission'},
]

const myProfile = [
    { name: 'Мой профиль', href: '/profile'}
]

defineProps({
    isAdmin: {
        type: Boolean,
        required: true,
    }
})

const mobileMenuOpen = ref(false)


</script>
