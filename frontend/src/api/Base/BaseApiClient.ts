import axios from "axios"

export const defaultHeaders = {
    "Content-Type": "application/json"
}

export const AIPlaygroundApiClient = axios.create({baseURL:"http://localhost/api/",headers:defaultHeaders});