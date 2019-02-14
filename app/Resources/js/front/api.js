const host = "http://api.pullup.online";

export const login = (username, password) => {
    let object = {
        method: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            _username: username,
            _password: password
        })
    };

    return fetch(host + '/login_check', object)
        .then(response => response.json())
        .then((response) => {
            if (!response.token) {
                throw "Token not received"
            }

            return response.token
        })
        .catch((error) => {
            /**
             * @TODO implement offline checker
             */
            if (error.toString() === 'TypeError: Network request failed') {
                throw "Network error";
            }

            throw error;
        });
}

const _fetch = (url, headers) => {
    return fetch(host + url, headers)
        .then((response) => {
            if (response.ok) {
                return response;
            }

            throw new Error(response.statusText);
        })
        .then(response => response.json())
        .then(response => response)
        .catch((error) => {
            if (error.toString() === 'TypeError: Network request failed') {
                throw "Network error";
            }

            throw error;
        });

}

export const fetchExercises = () => {
    let token = window.localStorage.getItem('_t');
    let object = {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json",
            "Content-Type": "application/json"
        }
    };

    return _fetch('/secured/exercise/list', object);
}

export const createExercise = (name, isCardio) => {
    let token = window.localStorage.getItem('_t');
    let object = {
        method: "POST",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name,
            is_cardio: isCardio
        })
    };

    return _fetch('/secured/exercise/create', object);
}

export const updateExercise = (exerciseId, name, description, isCardio) => {
    let token = window.localStorage.getItem('_t');
    let object = {
        method: "PUT",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name,
            description: description,
            is_cardio: isCardio
        })
    };

    return _fetch('/secured/exercise/' + exerciseId + '/update', object);
}

export const removeExercise = (exerciseId) => {
    let token = window.localStorage.getItem('_t');
    let object = {
        method: "DELETE",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json",
            "Content-Type": "application/json"
        }
    };

    return _fetch('/secured/exercise/' + exerciseId + '/delete', object);
}

export const createExerciseVariant = (exerciseId, name) => {
    let token = window.localStorage.getItem('_t');
    let object = {
        method: "POST",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name
        })
    };

    return _fetch('/secured/exercise/' + exerciseId + '/variant/create', object);
}

export const removeExerciseVariant = (exerciseId, variantId) => {
    let token = window.localStorage.getItem('_t');
    let object = {
        method: "DELETE",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json",
            "Content-Type": "application/json"
        }
    };

    return _fetch('/secured/exercise/' + exerciseId + '/variant/' + variantId + '/delete', object);
}

export const updateExerciseVariant = (exerciseId, variantId, name) => {
    let token = window.localStorage.getItem('_t');
    let object = {
        method: "PUT",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name
        })
    };

    return _fetch('/secured/exercise/' + exerciseId + '/variant/' + variantId + '/update', object);
}
