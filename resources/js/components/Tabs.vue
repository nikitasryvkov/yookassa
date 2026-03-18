<template>
    <TabGroup>
        <!-- Заголовки вкладок -->
        <TabList class="flex border-b border-gray-300">
            <Tab v-for="(tab, index) in tabs" :key="index" v-slot="{ selected }">
                <button
                    :class="[
            'px-4 py-2 text-lg focus:outline-none',
            selected ? 'font-bold border-b-2 border-blue-500' : 'text-gray-500'
          ]"
                >
                    {{ tab.label }}
                </button>
            </Tab>
        </TabList>

        <!-- Контент вкладок -->
        <TabPanels class="p-4">
            <TabPanel v-for="(tab, index) in tabs" :key="index">
                <slot :name="tab.slot"></slot>
            </TabPanel>
        </TabPanels>
    </TabGroup>
</template>

<script setup>
    import { Tab, TabGroup, TabList, TabPanel, TabPanels } from '@headlessui/vue'

    const props = defineProps({
        tabs: {
            type: Array,
            required: true,
        },
    });
</script>
