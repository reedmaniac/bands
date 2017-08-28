<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" v-if="album">
                    <div class="panel-heading">
                    	<h2>
                    		{{album.name}}
                    	</h2>
                    </div>
                    
                    <div class="panel-body">
                    	<div v-if="album.band"><strong>Band: </strong><a v-bind:href="'/bands/' + album.band.id">{{album.band.name}}</a></div>
						<div><strong>Release Date: </strong>{{ album.release_date }}</div>
						<div><strong>Producer: </strong>{{ album.producer }}</div>
						<div><strong># of Tracks: </strong>{{ album.number_of_tracks }}</div>
						<div><strong>Genre: </strong>{{ album.genre }}</div>
						<div>
							<strong>
								<a v-bind:href="'/albums/edit/' + album.id">Edit</a>
								• 
								<a href="#" v-on:click.stop="deleteAlbum(album)" class="delete-link">Delete</a>
							</strong>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
    
    	data() {
    		return {
				album: null,
				albumId: this.id,
			}
    	},
    	props: [
    		'id'
    	],
        mounted() {
            console.log('ViewAlbum Component mounted.');
        },
        created() {
			this.fetchAlbum()
		},
        methods: {
			fetchAlbum() {
				var self = this
				var url = 'albums/' + self.albumId;
				
				axios.get(url).then(function (response) {
					console.log('Album loaded');
					self.album = response.data.data;
				})
			},
			deleteAlbum(album) {
				var self = this
				axios.delete('albums/' + album.id).then(function (response) {
					console.log('Album deleted');
					window.location = "/albums/";
				})
			}
		}
    }
</script>
