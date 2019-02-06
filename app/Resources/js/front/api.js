const host = "http://api.test.local:8000";

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

export const updateExercise = (id, name, description, isCardio) => {
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

    return _fetch('/secured/exercise/' + id + '/update', object);
}