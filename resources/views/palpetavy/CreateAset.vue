<template>
  <validation-observer
    ref="observer"
    v-slot="{ invalid }"
  >
    <form @submit.prevent="submit">
      <!-- Jenis Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Jenis Aset"
        rules="required"
      >
        <v-select
          v-model="jenisAset"
          :items="jenisAsetItems"
          item-text="teks"
          item-value="value"
          :error-messages="errors"
          label="Jenis Aset"
          prepend-icon="mdi-format-list-bulleted-square"
          data-vv-name="select"
          required
        ></v-select>
      </validation-provider>

      <!-- Nama Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Nama Aset"
        rules="required|min:10"
      >
        <v-text-field
          v-model="namaAset"
          :error-messages="errors"
          label="Nama Aset"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Nomor Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Nomor Aset"
        rules="required|min:10"
      >
        <v-text-field
          v-model="nomorAset"
          :counter="10"
          :error-messages="errors"
          label="Nomor Aset"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Tahun Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Tahun Aset"
        rules="required|digits:4"
      >
        <v-text-field
          v-model="tahunAset"
          :counter="4"
          :error-messages="errors"
          label="Tahun Aset"
          prepend-icon="mdi-calendar-clock"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Perolehan Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Perolehan Aset"
        rules="required|min:9"
      >
        <v-text-field
          v-model="perolehanAset"
          :error-messages="errors"
          label="Perolehan Aset"
          prepend-icon="mdi-cash-multiple"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Jenis Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Status Aset"
        rules="required"
      >
        <v-select
          v-model="statusAset"
          :items="statusAsetItems"
          item-text="teks"
          item-value="value"
          :error-messages="errors"
          label="Status Aset"
          prepend-icon="mdi-format-list-bulleted-square"
          data-vv-name="select"
          required
        ></v-select>
      </validation-provider>

      <!-- Lokasi Sertifikat Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Lokasi Sertifikat Aset"
        rules="required|min:10"
      >
        <v-text-field
          v-model="lokasiSertifikatAset"
          :error-messages="errors"
          label="Lokasi Sertifikat Aset"
          prepend-icon="mdi-cash-multiple"
          required
        ></v-text-field>
      </validation-provider>



      <!-- Kepemilikan Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Kepemilikan Aset"
        rules="required|min:10"
      >
        <v-text-field
          v-model="kepemilikanAset"
          :error-messages="errors"
          label="Kepemilikan Aset"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Pemegang Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Pemegang Aset"
        rules="required|min:10"
      >
        <v-text-field
          v-model="pemegangAset"
          :error-messages="errors"
          label="Pemegang Aset"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Kelurahan Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Kelurahan Aset"
        rules="required|min:5"
      >
        <v-text-field
          v-model="kelurahanAset"
          :error-messages="errors"
          label="Kelurahan Aset"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Kecamatan Aset -->
      <validation-provider
        v-slot="{ errors }"
        name="Kecamatan Aset"
        rules="required|min:5"
      >
        <v-text-field
          v-model="kecamatanAset"
          :error-messages="errors"
          label="Kecamatan Aset"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Alamat Aset  -->
      <validation-provider
        v-slot="{ errors }"
        name="Alamat Aset"
        rules="required|max:255"
      >
        <v-text-field
          v-model="alamatAset"
          :error-messages="errors"
          :counter="255"
          label="Alamat Aset"
          required
        ></v-text-field>
      </validation-provider>

      <!-- Keterangan Aset  -->
      <validation-provider
        v-slot="{ errors }"
        name="Keterangan Aset"
        rules="min:10"
      >
        <v-text-field
          v-model="keteranganAset"
          :error-messages="errors"
          label="Keterangan Aset"
        ></v-text-field>
      </validation-provider>


<!--       <validation-provider
        v-slot="{ errors }"
        name="phoneNumber"
        :rules="{
          required: true,
          digits: 7,
          regex: '^(71|72|74|76|81|82|84|85|86|87|88|89)\\d{5}$'
        }"
      >
        <v-text-field
          v-model="phoneNumber"
          :counter="7"
          :error-messages="errors"
          label="Phone Number"
          required
        ></v-text-field>
      </validation-provider>

      <validation-provider
        v-slot="{ errors }"
        name="email"
        rules="required|email"
      >
        <v-text-field
          v-model="email"
          :error-messages="errors"
          label="E-mail"
          required
        ></v-text-field>
      </validation-provider> -->

      <v-btn
        class="mr-4"
        type="submit"
        :disabled="invalid"
      >
        submit
      </v-btn>
      
      <v-btn @click="clear">
        reset
      </v-btn>
    </form>
  </validation-observer>
</template>

<script>
  import { required, digits, email, max, regex, min } from 'vee-validate/dist/rules'
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'

  setInteractionMode('eager')

  extend('digits', {
    ...digits,
    message: '{_field_} needs to be {length} digits. ({_value_})',
  })

  extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })

  extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
  })

  extend('min', {
    ...min,
    message: '{_field_} may not be lesser than {length} characters',
  })

  extend('regex', {
    ...regex,
    message: '{_field_} {_value_} does not match {regex}',
  })

  extend('email', {
    ...email,
    message: 'Email must be valid',
  })

  export default {
    components: {
      ValidationProvider,
      ValidationObserver,
    },
    data: () => ({
      namaAset: '',
      nomorAset: '',
      kepemilikanAset: '',
      pemegangAset: '',
      tahunAset: '',
      perolehanAset: '',
      alamatAset: '',
      kelurahanAset: '',
      kecamatanAset: '',
      keteranganAset: '',
      select: null,
      items: [
        'Item 1',
        'Item 2',
        'Item 3',
        'Item 4',
      ],
      jenisAsetItems:[
        {teks: 'Mobil Dinas', value: 'mobil'},
        {teks: 'Motor Dinas', value: 'motor'},
        {teks: 'Tanah', value: 'tanah'},
        {teks: 'Gedung', value: 'gedung'},
        {teks: 'Rumah Dinas', value: 'rumah_dinas'},
        {teks: 'Fasilitas Umum', value: 'fasilitas_umum'},
        {teks: 'Elektronik', value: 'elektronik'},
      ],
      statusAsetItems:[
        {teks: 'Surat Hak Milik', value: 'hak_milik'},
        {teks: 'Surat Hak Guna Bangunan', value: 'hak_guna_bangunan'},
        {teks: 'Surat Hak Pakai', value: 'hak_pakai'},
        {teks: 'Surat Keterangan Tanah', value: 'skt_spt'},
        {teks: 'Surat Hak Pengelolaan', value: 'hak_pengelolaan'},
        {teks: 'Hibah', value: 'hibah'},
      ],
    }),

    methods: {
      submit () {
        this.$refs.observer.validate()
        alert(this.namaAset)
      },
      clear () {
        this.namaAset = ''
        this.nomorAset = ''
        this.kepemilikanAset = ''
        this.pemegangAset = ''
        this.tahunAset = ''
        this.perolehanAset = ''
        this.alamatAset = ''
        this.kelurahanAset = ''
        this.kecamatanAset = ''
        this.keteranganAset = ''
        this.jenisAset = null
        this.statusAset = null
        this.$refs.observer.reset()
      },
    },
  }
</script>