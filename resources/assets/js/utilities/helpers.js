window.dbPromise = idb.open('ingredients-store', 1, db => {
    if (!db.objectStoreNames.contains('ingredients')) {
        db.createObjectStore('ingredients', {
            keyPath: 'id'
        })
    }
    if (!db.objectStoreNames.contains('sync-ingredients')) {
        db.createObjectStore('sync-ingredients', {
            keyPath: 'id'
        })
    }
})

window.indexData = function indexData(st, data) {
    return dbPromise   
        .then(db => {
            let tx = db.transaction(st, 'readwrite')
            let store = tx.objectStore(st)
            store.put(data)
            return tx.complete
        })
}

window.readIndexedData = function readIndexedData(st) {
    return dbPromise
        .then(db => {
            let tx = db.transaction(st, 'readonly')
            let store = tx.objectStore(st)
            return store.getAll()
        })
}

window.clearIndexedData = function clearIndexedData(st){
    return dbPromise
        .then(db => {
            let tx = db.transaction(st, 'readwrite')
            let store = tx.objectStore(st)
            store.clear()
            return tx.complete
        })
}

window.deleteItemFromIndexedData = function deleteItemFromIndexedData(st, itemID) {
    return dbPromise
        .then(db => {
            let tx = db.transaction(st, 'readwrite')
            let store = tx.objectStore(st)
            store.delete(itemID)
            return tx.complete
        })
        .then(() => console.log('Item Deleted'))
}

window.urlBase64ToUint8Array = function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
      .replace(/\-/g, '+')
      .replace(/_/g, '/');
  
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
  
    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

window.handleGesure = function handleGesure(deltaX) {
    if (deltaX > 100) {        
        return 'left'
    }
    else if (deltaX < - 100) {
        return 'right'
    }
    else {
        return 'tap'
    }
    
}