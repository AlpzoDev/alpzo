<template>
    <FwbAlert class="mb-4" type="info" border>
        <FwbP size="sm">
            PHP version and Nodejs version links
        </FwbP>

        <div class="flex gap-2 mt-2">
            <FwbButton color="alternative" href="/php-versions">PHP Versions</FwbButton>
            <FwbButton color="alternative" href="/node-versions">Node Versions</FwbButton>
        </div>
    </FwbAlert>
    <fwb-accordion>
        <fwb-accordion-panel v-for="(service, index) in services" :key="index">
            <fwb-accordion-header>{{service.name}}</fwb-accordion-header>
            <fwb-accordion-content>
                <serviceForm :service="service"/>
            </fwb-accordion-content>
        </fwb-accordion-panel>
    </fwb-accordion>

</template>

<script setup>
import {
    FwbAccordion,
    FwbAccordionContent,
    FwbAccordionHeader,
    FwbAccordionPanel,
    FwbAlert,
    FwbButton,
    FwbP,
} from 'flowbite-vue'
import serviceForm from "@/Components/setting/service/form.vue";


defineProps({
    services: Array
})
</script>
