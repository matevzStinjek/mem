<template>
    <div class="date-range-input">
        <date-input
            :theme="theme"
            :size="size"
            :value="value && value.from"
            :min-date="minDate"
            :max-date="maxDate"
            :date-format="dateFormat"
            :date-format-focus="dateFormatFocus"
            :date-before-min-date-error-message="dateBeforeMinDateErrorMessage"
            :date-after-max-date-error-message="dateAfterMaxDateErrorMessage"
            :track-name="trackName"
            label="Start date"
            @input="onFromInput"
            @keyup="$emit('keyup', $event)"
            @blur="$emit('blur', $event)">

            <icon slot="before" name="calendar" />
        </date-input>

        <div :class="{'date-range-input__separator--with-margin': !!separator}" class="date-range-input__separator">{{ separator }}</div>

        <date-input
            :theme="theme"
            :size="size"
            :value="value && value.to"
            :min-date="minDate"
            :max-date="maxDate"
            :date-format="dateFormat"
            :date-format-focus="dateFormatFocus"
            :date-before-min-date-error-message="dateBeforeMinDateErrorMessage"
            :date-after-max-date-error-message="dateAfterMaxDateErrorMessage"
            :track-name="trackName"
            label="End date"
            @input="onToInput"
            @keyup="$emit('keyup', $event)"
            @blur="$emit('blur', $event)">

            <icon slot="before" name="calendar" />
        </date-input>
    </div>
</template>

<script>
import DateInput from './DateInput.vue'
import Icon from './Icon.vue'

export default {
    components: {
        DateInput,
        Icon,
    },
    props: {
        theme: { type: String, default: 'dark' },
        size: { type: String, default: 'normal' },
        value: { type: Object },
        minDate: { type: Date },
        maxDate: { type: Date },
        dateFormat: { type: String },
        dateFormatFocus: { type: String, required: false },
        separator: { type: String },
        dateBeforeMinDateErrorMessage: { type: String, required: false },
        dateAfterMaxDateErrorMessage: { type: String, required: false },
        trackName: { type: String, default: 'dateRangeInput' },
    },
    methods: {
        onFromInput (value) {
            this.$emit('input', { from: value, to: this.value && this.value.to })
            this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'input-from' })
        },
        onToInput (value) {
            this.$emit('input', { from: this.value && this.value.from, to: value })
            this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'input-to' })
        },
    },
}
</script>

<style lang="less" scoped>
.date-range-input {
    display: flex;
    min-width: 475px;

    &__separator {
        margin: 17px 7.5px 0 7.5px;

        &--with-margin {
            margin: 17px 20px 0 20px;
        }
    }
}
</style>
