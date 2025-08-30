<template>
    <input 
        type="file" 
        class="form-control" 
        accept="image/*" 
        @change="upload"
    >
</template>

<script>
export default {
    props: {
        index: { type: Number, required: true },
        item: { type: Object, required: true }
    },
    methods: {
        upload(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                this.$emit('ready', {
                    index: this.index,
                    item: this.item,
                    file: files[0]
                });
            }
        }
    }
}
</script>
