import '../admin/app.css'


import Splide from '@splidejs/splide';

global.Splide = Splide;

new Splide('.sliderCampaign', {
    perPage: 3,
    pagination: false,
}).mount();