import { Platform } from "../../components/shared/types/Platform";
import { AIPlaygroundApiClient } from "../Base/BaseApiClient";
import { PlatformModel } from "../Models/PlatformModel";

export const PlatformsApiClient={
    urlPath:"platforms",

    getAllAsync():Promise<PlatformModel[]>{
        return AIPlaygroundApiClient.get<PlatformModel[]>(this.urlPath).then((response)=>response.data
    );
    },

    getOneAsync(id:Number):Promise<PlatformModel>{
    return AIPlaygroundApiClient.get<PlatformModel>(this.urlPath+'/'+id).then((response)=>response.data)
    }
}