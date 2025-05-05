import './bootstrap';
import { Stepper } from './stepper';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask'

Alpine.plugin(mask);

window.Alpine = Alpine;
window.Stepper = Stepper;

Alpine.start();
