// import React from 'react';
// import ReactDOM from 'react-dom';
// import './index.css';
// import App from './App';
// import * as serviceWorker from './serviceWorker';

// ReactDOM.render(<App />, document.getElementById('root'));

// // If you want your app to work offline and load faster, you can change
// // unregister() to register() below. Note this comes with some pitfalls.
// // Learn more about service workers: https://bit.ly/CRA-PWA
// serviceWorker.unregister();

import React from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter } from 'react-router-dom';

import {applyMiddleware, createStore } from 'redux'
import { Provider } from 'react-redux'

import rootReducer from './reducers'

// Logger with default options
import logger from 'redux-logger'

import './index.css';
import App from './App';
import * as serviceWorker from './serviceWorker';

// persist
import { persistStore, persistReducer } from 'redux-persist';
import storage from 'redux-persist/lib/storage';
import { PersistGate } from 'redux-persist/integration/react'

// const persistConfig = {
//     key: 'authType',
//     storage: storage,
//     whitelist: ['authType'] // which reducer want to store
// };
const persistConfig = {
    key: 'root',
    storage,
}

const pReducer = persistReducer(persistConfig, rootReducer);
// persist

const store = createStore(pReducer, applyMiddleware(logger));
const persistor = persistStore(store);

ReactDOM.render((<Provider store={store}>
                    <PersistGate loading={null} persistor={persistor}>
                        <BrowserRouter>
                            <App />
                        </BrowserRouter>
                    </PersistGate>
                </Provider>), document.getElementById('root'));

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
