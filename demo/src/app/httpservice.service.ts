import { Inject, Injectable } from "@angular/core";
import {HttpClient, HttpHeaders} from '@angular/common/http';
import{ Observable} from 'rxjs';
@Injectable()
export class HTTPService{
    headers;
    constructor(private http: HttpClient){
        this.headers = new HttpHeaders({
            'Content-type': 'application/json'
        });
    }

    sendGETRequest(url): Observable<ArrayBuffer>{
    return this.http.get(url,this.headers)
        //     .map(res => res)
        //     .catch((err) => {
        //         return new Observable.throw(err);
        //     })
    }
    
}