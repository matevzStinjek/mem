<template>
    <input-element
        :theme="theme"
        ref="input"
        :size="size"
        :value="formattedValue"
        :label="label || 'Enter date'"
        :error="errorText"
        @input="onInput"
        @focus="onFocus"
        @blur="onBlur"
        @keyup="$emit('keyup', $event)">

        <slot slot="before" name="before" />
        <slot slot="left" name="left" />
        <slot slot="right" name="right" />
    </input-element>
</template>

<script>
import moment from 'moment'
import Input from './Input.vue'
import { compareDate } from './helpers/utils'

export default {
    components: {
        inputElement: Input,
    },
    props: {
        theme: { type: String, default: 'dark' },
        size: { type: String, default: 'normal' },
        label: { type: String },
        value: { type: Date },
        minDate: { type: Date },
        maxDate: { type: Date },
        dateFormat: { type: String },
        dateFormatFocus: { type: String, required: false },
        dateBeforeMinDateErrorMessage: { type: String, required: false },
        dateAfterMaxDateErrorMessage: { type: String, required: false },
        trackName: { type: String, default: 'dateInput' },
    },
    data () {
        return {
            inFocus: false,
            textValue: null,
        }
    },
    computed: {
        formattedValue () {
            if (this.textValue) {
                return this.textValue
            }
            if (!this.value) {
                return null
            }
            if (this.inFocus) {
                return moment(this.value).format(this.dateFormatFocus || this.dateFormat)
            }
            return moment(this.value).format(this.dateFormat)
        },
        momentDate () {
            if (!this.textValue) {
                return null
            }
            return moment(this.textValue, this.dateFormatFocus || this.dateFormat)
        },
        errorText () {
            if (this.momentDate && this.inFocus) {
                if (!this.momentDate.isValid()) {
                    return 'Date is not valid'
                } else if (!this.isDateAfterMinDate(this.momentDate.toDate())) {
                    return this.dateBeforeMinDateErrorMessage || 'Date not in required range'
                } else if (!this.isDateBeforeMaxDate(this.momentDate.toDate())) {
                    return this.dateAfterMaxDateErrorMessage || 'Date not in required range'
                }
            }
            return null
        },
    },
    methods: {
        isDateAfterMinDate (date) {
            return !this.minDate || compareDate(date, this.minDate) >= 0
        },
        isDateBeforeMaxDate (date) {
            return !this.maxDate || compareDate(date, this.maxDate) <= 0
        },
        isDateValid (date) {
            return this.isDateAfterMinDate(date) && this.isDateBeforeMaxDate(date)
        },
        onInput (value) {
            this.textValue = value

            if (this.momentDate && this.momentDate.isValid()) {
                const date = this.momentDate.toDate()
                if (this.isDateValid(date)) {
                    this.$emit('input', date)
                    this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'input' })
                }
            } else {
                this.$emit('input', null)
                this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'input' })
            }
        },
        onFocus () {
            this.textValue = null
            this.inFocus = true
            this.$root.$emit('tracking-event', { type: 'input', label: this.trackName, trigger: 'focus' })
        },
        onBlur (ev) {
            this.textValue = null
            this.inFocus = false
            this.$emit('blur', ev)
        },
        focus () {
            this.$refs.input.focus()
        },
    },
}
</script>
