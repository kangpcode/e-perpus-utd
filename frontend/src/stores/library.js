import { defineStore } from 'pinia'
import { MOCK_BOOKS, MOCK_LOANS, CATEGORIES } from '../data/mockBooks'

export const useLibraryStore = defineStore('library', {
  state: () => ({
    books: [...MOCK_BOOKS],
    categories: [...CATEGORIES],
    selectedCategory: 'all',
    searchQuery: '',
    selectedFormat: 'all', // 'all' | 'physical' | 'pdf' | 'epub'
    selectedAvailability: 'all', // 'all' | 'available'
    currentRole: 'mahasiswa', // 'tamu' | 'mahasiswa' | 'dosen' | 'pustakawan' | 'admin'
    authToken: localStorage.getItem('digipus_token') || '',
    userProfile: {
      name: 'Rafi Pratama',
      nim: 'NIM: 22010884',
      faculty: 'Fakultas Ilmu Komputer',
      major: 'Teknik Informatika (S1)',
      maxBorrowQuota: 5,
      currentBorrowed: 2,
      activeFine: 0,
    },
    loans: [...MOCK_LOANS],
    wishlist: [1, 2], // book IDs
    activeDetailBook: null,
    activeReadingBook: null,
    isPwaInstalled: false,
    offlineMode: false,
    toastMessage: null,
    toastType: 'info', // 'success' | 'info' | 'error'
    isApiConnected: false,
  }),

  getters: {
    filteredBooks(state) {
      return state.books.filter(book => {
        // Category filter
        const matchCategory = state.selectedCategory === 'all' || 
          book.category === state.selectedCategory ||
          (book.category_rel && book.category_rel.slug === state.selectedCategory)
        
        // Search query filter
        const query = state.searchQuery.toLowerCase().trim()
        const authorName = typeof book.author === 'string' ? book.author : (book.authors ? book.authors.map(a => a.name).join(', ') : '')
        const matchQuery = !query || 
          book.title.toLowerCase().includes(query) ||
          authorName.toLowerCase().includes(query) ||
          book.isbn.toLowerCase().includes(query) ||
          (book.tags && book.tags.some(tag => tag.toLowerCase().includes(query)))

        // Format filter
        let matchFormat = true
        if (state.selectedFormat === 'physical') {
          matchFormat = book.isPhysical || book.is_physical
        } else if (state.selectedFormat === 'pdf') {
          matchFormat = (book.formatType === 'pdf' || book.format_type === 'pdf' || book.format_type === 'hybrid')
        } else if (state.selectedFormat === 'epub') {
          matchFormat = (book.formatType === 'epub' || book.format_type === 'epub')
        }

        // Availability filter
        let matchAvailability = true
        const stockAvailable = book.availableStock !== undefined ? book.availableStock : book.available_stock
        if (state.selectedAvailability === 'available') {
          matchAvailability = stockAvailable > 0
        }

        return matchCategory && matchQuery && matchFormat && matchAvailability
      })
    },

    isBookInWishlist: (state) => (bookId) => {
      return state.wishlist.includes(bookId)
    },

    isBookBorrowed: (state) => (bookId) => {
      return state.loans.some(loan => (loan.bookId === bookId || loan.book_id === bookId) && loan.status === 'Dipinjam')
    }
  },

  actions: {
    async initFromBackend() {
      try {
        const res = await fetch('/api/books')
        if (res.ok) {
          const data = await res.json()
          if (data && data.data && data.data.length > 0) {
            this.books = data.data.map(b => ({
              ...b,
              author: b.authors && b.authors.length > 0 ? b.authors.map(a => a.name).join(', ') : 'Pustaka Digitech',
              publisher: b.publisher ? b.publisher.name : 'Digitech University Press',
              category: b.category ? b.category.slug : 'umum',
              categoryName: b.category ? b.category.name : 'Umum',
              coverGradient: b.cover_gradient || 'from-blue-700 via-indigo-800 to-blue-950',
              coverColor: b.cover_color || '#1E4FA3',
              availableStock: b.available_stock,
              stock: b.total_stock,
              borrowCount: b.borrow_count,
              format: b.format_type === 'physical' ? 'Buku Fisik' : (b.format_type === 'pdf' ? 'E-Book PDF' : 'E-Book EPUB'),
              formatType: b.format_type,
              isPhysical: b.is_physical,
              isDigital: b.is_digital,
              sampleContent: b.ebook && b.ebook.sample_content ? b.ebook.sample_content : [b.synopsis],
            }))
            this.isApiConnected = true
          }
        }
      } catch (err) {
        console.warn('Backend API offline or unreachable, using local reactive state.', err)
      }
    },

    async setRole(role) {
      this.currentRole = role
      const roleCredentials = {
        admin: { email: 'admin@digitech.ac.id', password: 'password' },
        pustakawan: { email: 'pustakawan@digitech.ac.id', password: 'password' },
        dosen: { email: 'dosen@digitech.ac.id', password: 'password' },
        mahasiswa: { email: 'mahasiswa@digitech.ac.id', password: 'password' },
      }

      if (roleCredentials[role]) {
        try {
          const res = await fetch('/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(roleCredentials[role])
          })
          if (res.ok) {
            const data = await res.json()
            this.authToken = data.token
            localStorage.setItem('digipus_token', data.token)
            this.userProfile = {
              name: data.user.name,
              nim: data.user.nim_nidn,
              faculty: data.user.faculty,
              major: data.user.major,
              maxBorrowQuota: data.user.max_borrow_quota,
              currentBorrowed: data.user.current_borrowed,
              activeFine: 0,
            }
            this.showToast(`Autentikasi API aktif: Role ${role.toUpperCase()}`, 'success')
            return
          }
        } catch (e) {
          console.warn('API login skipped, updating state locally', e)
        }
      }

      // Local fallback
      if (role === 'dosen') {
        this.userProfile = {
          name: 'Dr. Hendra Gunawan, M.T.',
          nim: 'NIDN: 0412097801',
          faculty: 'Fakultas Ilmu Komputer',
          major: 'Dosen Tetap Informatika',
          maxBorrowQuota: 15,
          currentBorrowed: 3,
          activeFine: 0,
        }
      } else if (role === 'pustakawan') {
        this.userProfile = {
          name: 'Siti Rahmawati, S.Sos.',
          nim: 'NIP: 19880415201201',
          faculty: 'UPT Perpustakaan',
          major: 'Kepala Layanan Sirkulasi',
          maxBorrowQuota: 99,
          currentBorrowed: 0,
          activeFine: 0,
        }
      } else if (role === 'admin') {
        this.userProfile = {
          name: 'Bima Administrator',
          nim: 'ADM-SYS-01',
          faculty: 'Biro Sistem Informasi',
          major: 'Super Administrator',
          maxBorrowQuota: 99,
          currentBorrowed: 0,
          activeFine: 0,
        }
      } else if (role === 'tamu') {
        this.userProfile = {
          name: 'Pengunjung Tamu',
          nim: 'Guest User',
          faculty: 'Masyarakat / Siswa',
          major: 'Akses Publik',
          maxBorrowQuota: 0,
          currentBorrowed: 0,
          activeFine: 0,
        }
      } else {
        this.userProfile = {
          name: 'Rafi Pratama',
          nim: 'NIM: 22010884',
          faculty: 'Fakultas Ilmu Komputer',
          major: 'Teknik Informatika (S1)',
          maxBorrowQuota: 5,
          currentBorrowed: this.loans.length,
          activeFine: 0,
        }
      }
      this.showToast(`Beralih ke tampilan role: ${role.toUpperCase()}`, 'info')
    },

    toggleWishlist(bookId) {
      const index = this.wishlist.indexOf(bookId)
      if (index === -1) {
        this.wishlist.push(bookId)
        this.showToast('Buku berhasil ditambahkan ke Rak Favorit!', 'success')
      } else {
        this.wishlist.splice(index, 1)
        this.showToast('Buku dihapus dari Rak Favorit.', 'info')
      }
    },

    async borrowBook(book) {
      if (this.currentRole === 'tamu') {
        this.showToast('Silakan login sebagai Mahasiswa/Dosen untuk meminjam buku.', 'error')
        return false
      }

      if (this.userProfile.currentBorrowed >= this.userProfile.maxBorrowQuota) {
        this.showToast(`Batas kuota pinjam (${this.userProfile.maxBorrowQuota} buku) sudah tercapai!`, 'error')
        return false
      }

      if (this.isBookBorrowed(book.id)) {
        this.showToast('Buku ini sudah ada dalam daftar pinjaman aktif Anda!', 'info')
        return false
      }

      const available = book.availableStock !== undefined ? book.availableStock : book.available_stock
      if (available <= 0) {
        this.showToast('Stok habis! Anda dapat melakukan reservasi antrean.', 'info')
        return false
      }

      // Try Laravel API if token exists
      if (this.authToken) {
        try {
          const res = await fetch('/api/loans/borrow', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'Authorization': `Bearer ${this.authToken}`
            },
            body: JSON.stringify({ book_id: book.id })
          })

          const data = await res.json()
          if (!res.ok) {
            this.showToast(data.message || 'Gagal meminjam buku.', 'error')
            return false
          }
        } catch (err) {
          console.warn('API error, falling back to local simulation', err)
        }
      }

      // Decrement stock in local state
      if (book.availableStock !== undefined) book.availableStock -= 1
      if (book.available_stock !== undefined) book.available_stock -= 1
      book.borrowCount = (book.borrowCount || 0) + 1

      const today = new Date()
      const dueDate = new Date()
      dueDate.setDate(today.getDate() + 14)

      const newLoan = {
        id: `PINJ-${today.getFullYear()}-${Math.floor(100 + Math.random() * 900)}`,
        bookId: book.id,
        title: book.title,
        borrowDate: today.toISOString().split('T')[0],
        dueDate: dueDate.toISOString().split('T')[0],
        daysRemaining: 14,
        status: 'Dipinjam',
        format: book.format || (book.is_physical ? 'Buku Fisik' : 'E-Book'),
        copyCode: book.isPhysical ? `EX-${Math.floor(1000 + Math.random() * 9000)}` : 'DIGITAL-LIC',
        isOverdue: false,
        coverGradient: book.coverGradient || book.cover_gradient,
        extendCount: 0,
        maxExtend: 2
      }

      this.loans.unshift(newLoan)
      this.userProfile.currentBorrowed = this.loans.length
      this.showToast(`Berhasil meminjam "${book.title.slice(0, 30)}..."! Batas waktu 14 hari.`, 'success')
      return true
    },

    returnLoan(loanId) {
      const index = this.loans.findIndex(l => l.id === loanId)
      if (index !== -1) {
        const loan = this.loans[index]
        const book = this.books.find(b => b.id === loan.bookId)
        if (book) {
          if (book.availableStock !== undefined) book.availableStock += 1
          if (book.available_stock !== undefined) book.available_stock += 1
        }
        this.loans.splice(index, 1)
        this.userProfile.currentBorrowed = this.loans.length
        this.showToast('Buku berhasil dikembalikan. Terima kasih telah menjaga koleksi!', 'success')
      }
    },

    extendLoan(loanId) {
      const loan = this.loans.find(l => l.id === loanId)
      if (loan) {
        if (loan.extendCount >= loan.maxExtend) {
          this.showToast('Batas maksimal perpanjangan (2x) telah tercapai.', 'error')
          return
        }
        loan.extendCount += 1
        loan.daysRemaining += 7
        this.showToast('Masa peminjaman berhasil diperpanjang +7 hari!', 'success')
      }
    },

    openDetail(book) {
      this.activeDetailBook = book
    },

    closeDetail() {
      this.activeDetailBook = null
    },

    openReader(book) {
      this.activeReadingBook = book
    },

    closeReader() {
      this.activeReadingBook = null
    },

    showToast(message, type = 'info') {
      this.toastMessage = message
      this.toastType = type
      setTimeout(() => {
        if (this.toastMessage === message) {
          this.toastMessage = null
        }
      }, 4000)
    }
  }
})
