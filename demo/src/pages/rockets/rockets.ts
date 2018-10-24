import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { RocketService } from './rockets.service';

/**
 * Generated class for the RocketsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-rockets',
  templateUrl: 'rockets.html',
  providers: [RocketService]
})
export class RocketsPage {

  rockets: any;
  constructor(public navCtrl: NavController, public navParams: NavParams, private RocketService: RocketService) {
    this.RocketService.getData()
    .subscribe((data) => {
      this.rockets = data;
      console.log(this.rockets);
    })
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad RocketsPage');
  }

}
