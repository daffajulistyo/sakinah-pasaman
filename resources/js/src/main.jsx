import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import App from './app'
import './tailwind.css'
import './index.css'
import 'animate.css'
import { Provider } from 'react-redux'
import store from '@/redux'
import 'flowbite';

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <Provider store={store}>
      <App />
    </Provider>
  </StrictMode>
)
