<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">General Settings</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-3">
                        <h3>Links</h3>
                        <div class="row">
                            <sidebar_settings style="width: 100%;"></sidebar_settings>
                        </div> 
                    </div>
                    <div class="col col-9">
                        <h3 style="margin: 0 0 20px 0;">Users Logs</h3>
                        <div class="row container" style="padding:0 15px;">
                                <ul  v-for="log in  activity">
                                    <li>
                                        {{log.id}}#&nbsp;&nbsp;||&nbsp;&nbsp;
                                        {{log.properties}}&nbsp;&nbsp;||&nbsp;&nbsp;
                                        {{log.description}}&nbsp;&nbsp;||&nbsp;&nbsp;
                                        {{log.causer_id}} -- {{log.causer_type}}&nbsp;&nbsp;||&nbsp;&nbsp;
                                    </li>
                                </ul>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import ImageUploadPreview from '../../components/form/ImageUploadPreview.vue'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Typeahead from '../../components/form/Typeahead.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'
    import sidebar_settings from '../../components/layouts/SidebarSettings.vue'
    // import Deliverycondition from '../deliverycondition/index.vue'
    

    export default {
        computed: {
            second_currency(){
                return window.apex.second_currency
            },
            activity(){
                return window.apex.activity
            },
        },
        components: { ErrorText, Typeahead, ImageUploadPreview, Spinner ,sidebar_settings},
        mixins: [ form ],
      
    }
</script>
