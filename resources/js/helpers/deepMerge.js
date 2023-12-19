export default {
    // Функция глубокого слияния объектов
    deepMerge(current, updates) {
        for (let key of Object.keys(updates)) {
          if (!current.hasOwnProperty(key) || typeof updates[key] !== 'object') current[key] = updates[key];
          else this.deepMerge(current[key], updates[key]);
        }
        return current;
    }
   }