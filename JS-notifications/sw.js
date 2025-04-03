console.log("from service worker");
/*
//https://web-push-codelab.glitch.me/
const PUBLIC_KEY = "BGph4sjoXS8yTg9eZGDLrDW8r01cRU0DQDhuKoo6WED8UPxmTaC014GxqLKzF1JDfikWJnegLbFQ11ExGhOQeZ0";
//PRIVATE_KEY = "5HXOGrJ-VqhWxdAZ85eYIwhFD-XK4s80Jm5DSSz4g-M";
/// https://web-push-codelab.glitch.me/

// urlB64ToUint8Array is a magic function that will encode the base64 public key
// to Array buffer which is needed by the subscription option
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');

    const rawData = atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
  }


//https://stackoverflow.com/questions/45994933/changing-application-server-key-in-push-manager-subscription
self.addEventListener('install', async () => {
	console.log("install service worker");
  // This will be called only once when the service worker is activated.
  try {
    const options = {userVisibleOnly: true, applicationServerKey: urlBase64ToUint8Array(PUBLIC_KEY)}
    const subscription = await self.registration.pushManager.subscribe(options)
    console.log(JSON.stringify(subscription))
  } catch (err) {
    console.log('Error', err)
  }
})
*/