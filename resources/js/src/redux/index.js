import { configureStore } from "@reduxjs/toolkit";
import rootReducers from './reducers'
import Api from "../api";

const store = configureStore({
    reducer: rootReducers,
    middleware: getDefaultMiddleware => 
        getDefaultMiddleware({
            thunk: {
                extraArgument: new Api()
            }
        })
})

export default store