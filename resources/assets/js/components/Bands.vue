<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h2>
                    		Bands 
                    		<span style="float:right; font-size: 0.7em;">
                    			<a href="/bands/new">+ Add Band</a>
                    		</span>
                    	</h2>
                    </div>
                    
                    <table class="bands">
						<thead>
							<th><a href="#" @click="order('name')">Band</a></th>
							<th><a href="#" @click="order('start_date')">Start Date</a></th>
							<th><a href="#" @click="order('still_active')">Still Active?</a></th>
							<th>Website</th>
							<th>Edit</th>
							<th>Delete</th>
						</thead>
						
						<tbody>

						<tr class="band" v-for="band in bands">
						  <td><a v-bind:href="'/bands/' + band.id">
								{{ band.name }}
							</a>
						  </td>
						  <td>{{ band.start_date }}</td>
						  <td>{{ band.still_active }}</td>
						  <td><a :href="band.website">Website</a></td>
						  <td><a v-bind:href="'/bands/edit/' + band.id">Edit</a></td>
						  <td><a href="#" v-on:click.stop="deleteBand(band)" class="delete-link">Delete</a></td>
						  </tr>
						</tbody>
					</table>
				</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
    
    	data() {
    		return {
				bands: null,
				orderByProperty: 'name',
				sortProperty: 'asc',
			}
    	},
        mounted() {
            console.log('Bands Component mounted.');
        },
        created() {
			this.fetchBands()
		},
        methods: {
        	order(field, sort) {
				
				if (field == this.orderByProperty) {
					this.sortProperty = (this.sortProperty == 'desc') ? 'asc' : 'desc';
				} else {
					this.sortProperty = 'desc';
				}
				
				this.orderByProperty = field;
								
				this.fetchBands();
			},
			fetchBands() {
				var self = this
				
				var url = 'bands?orderby=' + self.orderByProperty + '&sort=' + self.sortProperty;
				
				axios.get(url).then(function (response) {
					console.log('Bands loaded');
					self.bands = response.data.data;
				})
			},
			deleteBand(band) {
				var self = this
				axios.delete('bands/' + band.id).then(function (response) {
					console.log('Band deleted');
					self.bands.splice(self.bands.indexOf(band), 1)
				})
			}
		}
    }
</script>
